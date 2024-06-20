<?php

namespace App\Lib;

class Ascend
{
    /**
     * 
     * @param $host
     * @param $user
     * @param $pass
     * @param $db
     * @param $conn
     * 
     * @method __construct
     * @method getRunningLimitsTrades@return json
     * @method connect @return string
     * @method updateRate @return bool 
     * @method setPips
     */

    public $host = 'localhost';
    public $user = 'root';
    public $pass = '';
    public $db = 'vinance';
    public $conn;

    public function __construct()
    {
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db) or die(mysqli_connect_error());
    }

    public function getRunningtLimitsTrades()
    {
        $sql = "SELECT * FROM `limit_trades` WHERE `status`= 0";
        $query = mysqli_query($this->conn, $sql);
        $limits = [];

        if (mysqli_num_rows($query) < 0) {
            return "Error";
        } else {
            $limits = mysqli_fetch_all($query, MYSQLI_ASSOC);
        }

        return json_encode($limits);
    }

    public function connect(string $symbol)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.iex.cloud/v1/data/CORE/QUOTE/$symbol?token=pk_32fc5dd308f04ef88684ccdc4ab36452",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        return $response[0]['latestPrice'];
    }

    public function updateRate($limits)
    {
        $success = false;
        $toReturn = [];
        mysqli_begin_transaction($this->conn);
        $current = [];

        try {
            foreach ($limits as $limit) {
                if ($limit['isCommodity']) {
                    $rate = $this->connect($limit['commodity']);
                    $toReturn[$limit['id']] = $rate;
                } elseif ($limit['isStock']) {
                    $rate = $this->connect($limit['stock']);
                    $toReturn[$limit['id']] = $rate;
                }
            }

            $status = 0;

            foreach ($limits as &$limit) {
                if (isset($toReturn[$limit['id']])) {
                    $price_was = $limit['price_is'] == NULL ? $limit['price_was'] : $limit['price_is'];
                    $sql = "UPDATE `limit_trades` SET `price_was` = ?, `price_is` = ? WHERE `id` = ? AND `status` = ?";
                    $stmt = mysqli_prepare($this->conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ddii", $price_was, $toReturn[$limit['id']], $limit['id'], $status);
                    $success = mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    if (!$success) {
                        throw new \Exception("Update failed");
                    }
                }
            }

            mysqli_commit($this->conn);
            $success = true;
        } catch (\Exception $e) {
            mysqli_rollback($this->conn);
            $success = false;
        }



        return $success;
    }


    public function setPips(array $limits)
    {

        $toSet = [];

        foreach ($limits as &$limit) {
            $priceIs = $limit['price_is'] ?? 0;
            $toSet[$limit['id']] =  number_format($priceIs, 2, '.', '');
        }

        $pip = [];

        foreach ($toSet as $key => $value) {
            $hundreth = substr($value, -1, 1);
            $hundreth = 0.01 * ($hundreth == 0 ? 5 : $hundreth);
            $pip[$key] = $hundreth;
        }


        foreach ($limits as $limit) {
            if (isset($pip[$limit['id']])) {
                if ($limit['price_was'] > $limit['stop_loss'] || $limit['price_was'] < $limit['take_profit']) {
                    if ($limit['price_is'] > $limit['price_was']) {
                        $limit['amount'] += $pip[$limit['id']];
                    } else if ($limit['price_is'] < $limit['price_was']) {
                        $limit['amount'] -= $pip[$limit['id']];
                    } else {
                        $limit['amount'] += 0;
                    }
                } else {
                    $limit['status'] = 1;
                }
            }
        }

        $this->updateWalletIfLimitTradeStatusIsTrue($limits);

        return $limits;
    }

    public function updateCalcultions(array $limits)
    {
        $success = false;

        try {
            mysqli_begin_transaction($this->conn);

            foreach ($limits as &$limit) {
                $sql = "UPDATE `limit_trades` SET `amount` = ?, `status` = ? WHERE `id`  = ?";
                $stmt = mysqli_prepare($this->conn, $sql);
                mysqli_stmt_bind_param($stmt, "dii", $limit['amount'], $limit['status'], $limit['id']);
                $success = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                if (!$success) {
                    throw new \Exception("Fail");
                }
            }

            mysqli_commit($this->conn);
            return true;
        } catch (\Exception $e) {
            mysqli_rollback($this->conn);
            return false;
        }
    }

    public function updateWalletIfLimitTradeStatusIsTrue(array $limits)
    {
        foreach ($limits as $limit) {

            if ($limit['status'] == 1) {
                $sql = "SELECT * FROM `wallets` WHERE `user_id` = " . $limit['user_id'] . " AND  `currency_id` = 3";

                $query = mysqli_query($this->conn, $sql);

                if (mysqli_num_rows($query) > 0) {
                    $balance = mysqli_fetch_assoc($query);
                    $newBalance = $balance['balance'];
                    $newBalance += $limit['amount'];
                    $sql2 = "UPDATE `wallets` SET `balance` = $newBalance  WHERE `user_id` = " . $limit['user_id'] . " AND  `currency_id` = 3";
                    $query2 = mysqli_query($this->conn, $sql2);
                }
            }
        }
    }
}

$ascend = new Ascend();

$limits = json_decode($ascend->getRunningtLimitsTrades(), true);

$ascend->updateRate($limits);

$limits = json_decode($ascend->getRunningtLimitsTrades(), true);

$toUpdate = $ascend->setPips($limits);

echo $ascend->updateCalcultions($toUpdate) . "\n";
