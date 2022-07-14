<?php
    class Database {
        private $data = array();
        private $filename = 'database.txt';
        private $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        private $class = ['Мотоциклы', 'Легковые', 'Грузовые', 'Автобусы', 'Трамвай', 'Троллейбусы', 'Тракторы', 'Самолеты'];
        private $engine = ['Бензин', 'Дизель', 'Гибрид', 'Электро'];
        private $body = ['Механическая', 'Автоматическая', 'Роботизированная', 'Вариатор'];

        public function fillDatabase($i) {
            file_put_contents($this->filename, '');
            $name = explode(' ', $this->lorem);
            for ($j = 0; $j < $i; $j++) {
                $temp = array(
                    $name[rand(0, count($name) - 1)] . ' ' . $name[rand(0, count($name) - 1)],
                    intdiv(rand(100000, 100000000), 10000) * 10000,
                    $this->class[rand(0, count($this->class) - 1)],
                    $this->engine[rand(0, count($this->engine) - 1)],
                    $this->body[rand(0, count($this->body) - 1)]
                );
                array_push($this->data, $temp);
            }
            $this->printToFile();
        }

        public function filter($criteria) {
            
            $this->updateDataBase();
            $data = $this->data;
            $temp = [];
            if (!$criteria['price_start']) $criteria['price_start'] = 0;
            if (!$criteria['price_end']) $criteria['price_end'] = 100000000;
            for ($i = 0; $i < count($data); $i++) {
                if ($criteria['price_start'] < intval($data[$i][1]) && intval($data[$i][1]) < $criteria['price_end']) {
                    array_push($temp, $data[$i]);
                }
            }
            unset($data);
            $data = $temp;
            unset($temp);
            $temp = array();
            if ($criteria['classes']) {
                for ($i = 0; $i < count($data); $i++) {
                    if (in_array($data[$i][2], $criteria['classes'])) {
                        array_push($temp, $data[$i]);
                    }
                }
                unset($data);
                $data = $temp;
                unset($temp);
                $temp = array();
            }
            if ($criteria['engines']) {
                for ($i = 0; $i < count($data); $i++) {
                    if (in_array($data[$i][3], $criteria['engines'])) {
                        array_push($temp, $data[$i]);
                    }
                }
                unset($data);
                $data = $temp;
                unset($temp);
                $temp = array();
            }
            if ($criteria['transmissions']) {
                for ($i = 0; $i < count($data); $i++) {
                    if (in_array($data[$i][4], $criteria['transmissions'])) {
                        array_push($temp, $data[$i]);
                    }
                }
                unset($data);
                $data = $temp;
                unset($temp);
                $temp = array();
            }
            return $data;
            return $criteria['classes'];
        }








        private function printToFile() {
            foreach ($this->data as $el){
                $temp = $el[0].' '.$el[1].' '.$el[2].' '.$el[3].' '.$el[4].PHP_EOL;
                file_put_contents($this->filename, $temp, FILE_APPEND);
            }
        }

        public function getDataBase() {
            if (!file_get_contents($this->filename)) {
                $this->fillDatabase(100);
                return $this->data;
            }
            return $this->updateResult(explode(PHP_EOL, file_get_contents($this->filename)));
        }

        private function updateDataBase() {
            $this->data = $this->updateResult(explode(PHP_EOL, file_get_contents($this->filename)));
        }

        private function updateResult($str) {
            $temp = array();
            for ($i = 0; $i < count($str); $i++) {
                $data = explode(' ', $str[$i]);
                $temp[$i][0] = $data[0] . ' ' . $data[1];
                for ($j = 2; $j < count($data); $j++) {
                    $temp[$i][$j - 1] = $data[$j];
                }
            }
            return $temp;
        }
    }

    $database = new Database();
    if ($_SERVER['HTTP_TYPE'] == 'start') {
        echo json_encode($database->getDataBase(), JSON_UNESCAPED_UNICODE);
    }
    if ($_SERVER['HTTP_TYPE'] == 'filter') {
        echo json_encode($database->filter($_GET), JSON_UNESCAPED_UNICODE);
    }
?>