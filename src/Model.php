<?php

namespace Niroee\EasyOrm;

use Exception;
use JsonSerializable;

use PDO;
use PDOStatement;

class Model implements JsonSerializable
{
    private const SELECT = 1;
    private const UPDATE = 2;
    private const INSERT = 3;
    private const DELETE = 4;
    private int $QueryType;
    private PDO $Connection;

    private string $Host;
    private string $Port;
    private string $User;
    private string $Pass;
    private string $DBName;

    private string $Query;
    private array $Conditions = [];
    private array $Values = [];
    private int $Limit = 0;
    private string $FinalQuery;

    protected string|null $Table = NULL;

    public function __construct()
    {
        $this->Host = $_ENV['DB_HOST'];
        $this->Port = $_ENV['DB_PORT'];
        $this->Pass = $_ENV['DB_PASS'];
        $this->User = $_ENV['DB_USER'];
        $this->DBName = $_ENV['DB_NAME'];

        $this->connect();
    }

    private function connect()
    {
        $Dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $this->Host, $this->Port, $this->DBName);

        $PDO = new PDO($Dsn, $this->User, $this->Pass, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);

        $PDO->exec('SET CHARACTER SET utf8mb4');

        $this->Connection = $PDO;

        $this->prepareTableNameAndQuery();
    }

    private function prepareTableNameAndQuery()
    {
        // Create table name by model
        if (is_null($this->Table)):
            $Array = explode("\\", static::class);
            $ClassName = end($Array);
            $Words = preg_split('/(?=[A-Z])/', $ClassName, flags: PREG_SPLIT_NO_EMPTY);
            array_walk($Words, function (&$Word) {
                $Word = strtolower($Word);
            });
            $this->Table = implode('_', $Words);
        endif;

        $this->Query = "SELECT * FROM `{$this->Table}`";
    }

    /**
     * @param string|array $Column
     * @return object
     */
    protected function select(string|array $Column = '*'): object
    {
        $this->QueryType = self::SELECT;
        if (is_array($Column)):
            $Column = implode(',', $Column);
        endif;

        if (empty($Column)):
            $Column = '*';
        endif;

        $this->Query = "SELECT $Column FROM `$this->Table`";

        return $this;
    }

    /**
     * @param array $Columns
     * @return Model
     */
    protected function insert(array $Columns): Model
    {
        $this->QueryType = self::INSERT;
        $Changes = [];
        foreach ($Columns as $Column):
            $Changes[] = '?';
            $this->Values[] = $Column;
        endforeach;

        $this->Query = "INSERT INTO `$this->Table`(" . implode(',', array_keys($Columns)) . ") VALUES(" . implode(',', $Changes) . ")";

        return $this;
    }

    /**
     * @param array $Columns
     * @return object
     */
    protected function update(array $Columns): object
    {
        $this->QueryType = self::UPDATE;
        $Changes = [];
        if (count($Columns) > 0):
            foreach ($Columns as $Key => $Column):
                $Changes[] = " $Key=?";
                $this->Values[] = $Column;
            endforeach;
        endif;

        $this->Query = "UPDATE `$this->Table` SET" . implode(',', $Changes);

        return $this;
    }

    /**
     * @return object
     */
    protected function delete(): object
    {
        $this->QueryType = self::DELETE;
        $this->Query = "DELETE FROM `$this->Table`";
        return $this;
    }

    protected function where(string $Column, string $Operator = '=', string|bool|int $Value = 1): Model
    {
        $this->Conditions[] = ['AND' => sprintf("`%s` %s ?", $Column, $Operator)];
        $this->Values[] = $Value;

        return $this;
    }

    protected function orWhere(string $Column, string $Operator = '=', string|bool|int $Value = 1): Model
    {

        $this->Conditions[] = ['OR' => sprintf("`%s` %s ?", $Column, $Operator)];
        $this->Values[] = $Value;
        return $this;
    }

    protected function andWhere(string $Column, string $Operator = '=', string|bool|int $Value = 1): Model
    {
        $this->Conditions[] = ['AND' => sprintf("`%s` %s ?", $Column, $Operator)];
        $this->Values[] = $Value;
        return $this;
    }

    protected function limit(int $Limit): static
    {
        $this->Limit = $Limit;
        return $this;
    }


    /**
     * @throws Exception
     */
    protected function exec(): int|Exception
    {
        if ($this->QueryType !== self::SELECT):
            return $this->prepare(Execute: true);
        else:
            throw new Exception("You can't use exec() function with select query");
        endif;
    }

    protected function get(): array
    {
        return $this->prepare();
    }

    protected function first(): object|bool
    {
        return $this->prepare(false);
    }

    protected function query(): string
    {
        return $this->prepare(GetQuery: true);
    }

    protected function json(): string
    {
        return $this->jsonSerialize();
    }

    protected function toArray(): array
    {
        $Object = $this->prepare();
        return array_map(fn($Item) => (array)$Item, (array)$Object);
    }


    private function prepare(bool $FetchAll = true, bool $Execute = false, bool $GetQuery = false): mixed
    {
        $Query = $this->Query;

        if ($this->Conditions):
            $Conditions = ' WHERE';
            foreach ($this->Conditions as $Key => $Condition):
                if ($Key !== 0):
                    $Conditions .= " " . array_key_first($Condition);
                endif;
                $Conditions .= " " . array_values($Condition)[0];
            endforeach;
            $Query = $this->Query . $Conditions;
        endif;

        if ($this->Limit > 0):
            $Query .= " limit " . $this->Limit;
        endif;

        $this->FinalQuery = $Query;

        if ($GetQuery) {
            return $this->FinalQuery;
        }

        return $this->execute($Execute, $FetchAll);
    }

    private function execute(bool $Execute = false, bool $All = true): mixed
    {
        $Statement = $this->Connection->prepare($this->FinalQuery);


        foreach ($this->Values as $Key => $Value):
            $Statement->bindValue($Key + 1, $Value);
        endforeach;

        $Statement->execute();

        return $Execute ? $Statement->rowCount() : $this->fetch($Statement, $All);
    }

    private function fetch(PDOStatement $Statement, bool $All): object|array|bool
    {
        return $All ? $Statement->fetchAll() : $Statement->fetch();
    }

    /**
     * @param string $Method
     * @param array $Args
     * @return mixed
     */
    public static function __callStatic(string $Method, array $Args)
    {
        return (new static)->$Method(...$Args);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->$name(...$arguments);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return string|bool data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize(): string|bool
    {
        return json_encode($this->prepare());
    }


}

