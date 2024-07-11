# home-Class-B

# base.php

session_start(); // 啟動 PHP 會話

// 定義一個名為 DB 的類別，用於處理數據庫操作
class DB
{
protected $table; // 定義一個受保護的屬性 table
protected $dsn = "mysql:host=localhost;charset=utf8;dbname=db15"; // 定義數據庫連接字符串
protected $pdo; // 定義一個受保護的屬性 pdo

    // 構造函數，初始化數據庫連接和表名稱
    public function __construct($table)
    {
        $this->table = $table; // 將傳入的表名稱賦值給屬性 table
        $this->pdo = new PDO($this->dsn, 'root', ''); // 創建 PDO 實例，用於數據庫操作
    }

    // 查詢表中的所有數據，可以接受條件和附加 SQL 語句
    public function all(...$arg)
    {
        $sql = "select * from `$this->table`"; // 基礎 SQL 查詢語句

        if (isset($arg[0])) {
            if (is_array($arg[0])) {
                $tmp = $this->a2s($arg[0]); // 將條件數組轉換為 SQL 語句
                $sql .= " where " . join(" && ", $tmp); // 添加 WHERE 條件
            } else {
                $sql .= $arg[0]; // 添加附加 SQL 語句
            }
        }

        if (isset($arg[1])) {
            $sql .= $arg[1]; // 添加附加 SQL 語句
        }

        // 執行 SQL 查詢並返回結果
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 查詢表中的單條數據，可以根據條件數組或 ID 查詢
    public function find($arg)
    {
        $sql = "select * from `$this->table` ";
        if (is_array($arg)) {
            $tmp = $this->a2s($arg); // 將條件數組轉換為 SQL 語句
            $sql .= " where " . join(" && ", $tmp); // 添加 WHERE 條件
        } else {
            $sql .= " where `id`='$arg'"; // 根據 ID 查詢
        }

        // 執行 SQL 查詢並返回結果
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // 保存數據，可以根據是否存在 ID 進行插入或更新操作
    public function save($arg)
    {
        if (isset($arg['id'])) {
            // 更新操作
            $tmp = $this->a2s($arg); // 將數據數組轉換為 SQL 語句
            $sql = "update `$this->table` set " . join(",", $tmp);
            $sql .= " where `id`='{$arg['id']}'";
        } else {
            // 插入操作
            $keys = array_keys($arg);
            $sql = "insert into `$this->table` (`" . join("`,`", $keys) . "`)
                   values('" . join("','", $arg) . "')";
        }

        // 執行 SQL 語句
        return $this->pdo->exec($sql);
    }

    // 刪除數據，可以根據條件數組或 ID 進行刪除
    public function del($arg)
    {
        $sql = "delete from `$this->table` ";
        if (is_array($arg)) {
            $tmp = $this->a2s($arg); // 將條件數組轉換為 SQL 語句
            $sql .= " where " . join(" && ", $tmp); // 添加 WHERE 條件
        } else {
            $sql .= " where `id`='$arg'"; // 根據 ID 刪除
        }

        // 執行 SQL 語句
        return $this->pdo->exec($sql);
    }

    // 計算表中的數據量，可以接受條件和附加 SQL 語句
    public function count(...$arg)
    {
        $sql = "select count(*) from `$this->table`"; // 基礎 SQL 查詢語句

        if (isset($arg[0])) {
            if (is_array($arg[0])) {
                $tmp = $this->a2s($arg[0]); // 將條件數組轉換為 SQL 語句
                $sql .= " where " . join(" && ", $tmp); // 添加 WHERE 條件
            } else {
                $sql .= $arg[0]; // 添加附加 SQL 語句
            }
        }

        if (isset($arg[1])) {
            $sql .= $arg[1]; // 添加附加 SQL 語句
        }

        // 執行 SQL 查詢並返回結果
        return $this->pdo->query($sql)->fetchColumn();
    }

    // 將數組轉換為 SQL 語句的輔助函數
    protected function a2s($array)
    {
        $tmp = [];
        foreach ($array as $key => $value) {
            $tmp[] = "`$key`='$value'";
        }

        return $tmp;
    }

}

// 查詢函數，用於執行 SQL 語句
function q($sql)
{
    $dsn = "mysql:host=localhost;charset=utf8;dbname=db15";
    $pdo = new PDO($dsn, 'root', '');
return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// 重定向函數，用於頁面跳轉
function to($url)
{
header("location:" . $url);
}

// 調試函數，用於輸出數組的結構和內容
function dd($array)
{
    echo "<pre>";
    print_r($array);
echo "</pre>";
}

// 創建數據庫表的實例
$Title = new DB('title');
$Ad = new DB('ad');
$Mvim = new DB('mvim');
$Image = new DB('image');
$News = new DB('news');
$Admin = new DB('admin');
$Menu = new DB('menu');
$Bottom = new DB('bottom');
$Total = new DB('views');

// 計算訪問量，並將其存儲在會話中
if (!isset($_SESSION['view'])) {
    $total = $Total->find(1);
    $total['view']++;
    $Total->save($total);
$\_SESSION['view'] = $total['view'];
}
