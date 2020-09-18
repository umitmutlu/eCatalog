<?php

/**
 * Bu sayfanın çağrıldığı heryerde session 'ı otomatik olarak başlatıyoruz.
 */
session_start();

/**
 * Bu sayfanın çağrıldığı her yerde undefined variable hatasını almamak için,
 * hata loglarından bazılarını gizliyoruz.
 */
error_reporting(~E_DEPRECATED & ~E_NOTICE);

class Db
{
    // MySQL Host
    protected $dbHost = 'localhost';

    // MySQL Username
    protected $dbemail = 'root';

    // MySQL Password
    protected $dbPassword = '';

    // MySQL Veritabanı Adı
    protected $dbName = 'ecat_db';

    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     *
     * @return mixed  Bağlantı başarısız olursa false
     * Başarılı olursa mysqli MySQLi object instance dönecek
     */
    /*$servername = 'localhost';
    $database = 'ecat_db';
    $username = 'root';
    $password = '';*/
    // Create connection
    public $conn = '';

    public function connect()
    {
        // Veritabanına bağlanmayı dene.
        if (!isset(self::$connection)) {
            self::$connection = @new mysqli($this->dbHost, $this->dbemail, $this->dbPassword, $this->dbName);
        }

        // Eğer bağlanamaz isen, false dön.
        if (self::$connection->connect_errno || self::$connection === false) {
            /**
             * Bu kısım geliştirile bilir.
             * Daha sonra dilerse hata kodu dönebilir.
             * Biz şimdilik false dönüyoruz.
             */
            return false;
        }

        self::$connection->select_db($this->dbName);
        self::$connection->set_charset("utf8");

        return self::$connection;
    }

    public function query($sql)
    {
        $conn = mysqli_connect('localhost', 'root', '', 'ecat_db');
        $conn-> set_charset("utf8");
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if (mysqli_query($conn, $sql)) {
            //echo '<script>alert("Başarıyla Eklendi !")</script>';
            //echo "<script>console.log('New record created successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }

    public function select($sql)
    {
        $conn = mysqli_connect('localhost', 'root', '', 'ecat_db');
        $conn->set_charset("utf8");
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sorgu = mysqli_query($conn, $sql);
        if (mysqli_query($conn, $sql)) {
            echo "";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
        return $sorgu;
    }

    public function error()
    {

        return '<br><strong>Hata Kodu:</strong> ' . self::$connection->connect_errno . ' <br><strong>Hata Mesajı:</strong> ' . self::$connection->connect_error;
    }
}
