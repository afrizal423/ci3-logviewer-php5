# CI3 Logviewer

Package untuk menampilkan log dari semua proses dengan tampilan yang _lebih eye catching_.

Package ini adalah pengembangan dari [Seun Matt](https://github.com/SeunMatt/codeigniter-log-viewer/tree/1.1.2), namun terkendala dikarenakan requirement PHP yang tidak cocok dengan server internal kita.
Maka dari itu dengan adanya repo ini semoga bisa membantu teman-teman dalam mendebug.

## Instalasi

- Download library ini
- Ekstrak file terlebih dahulu
- copy **satu folder** _logviewer_ kedalam folder ```system```
- buka file ```config.php```
    ubah log_threshold yang awalnya 0 jadi 1
    ```diff
    - $config['log_threshold'] = 0;
    + $config['log_threshold'] = 1;
    ```
    tambahkan 2 line berikut:
    ```php
    $config["clv_log_folder_path"] = APPPATH . "logs";
    $config["clv_log_file_pattern"] = "log-*.php";
    ```
- buat controller baru, contohnya:
    ```php
    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    require_once BASEPATH . 'logviewer/CILogViewer.php'; // <-- ini wajib ada

    class logViewerController extends CI_Controller {

        private $logViewer;

        public function __construct() {
            parent::__construct(); 
            $this->logViewer = new \CILogViewer\CILogViewer();
            //... silahkan logic yg bisa mengakses kesini user siapa
        }

        public function index() {
            echo $this->logViewer->showLogs();
            return;
        }
    }
    ```
- registrasikan routenya, agar lebih simpel mengakses url
    ```php
    $route['logs'] = "logViewerController/index";
    ```
    nantinya hanya tinggal mengakses ```http://base_url/logs```
- tambahkan ke ```.gitignore```
    ```
    logviewer
    application/views/cilogviewer
    ```
- selesai

jika ingin melihat dokumentasi lebih lanjut, silahkan [kunjungi ini](https://github.com/SeunMatt/codeigniter-log-viewer/wiki/CodeIgniter-3-Guide#viewing-log-files-via-api-calls).