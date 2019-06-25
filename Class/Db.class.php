<?php

    class Database
    {        
        public $fetch;
        public $from;
        public function test_if_x_in_db($table,$col,$x)
        {
            global $conn;
            $query = "SELECT * FROM " . $table . " WHERE " . $col . " = :element";
            if($stmt = $conn->prepare($query))
            {
                $stmt->bindParam(":element",$par_x,PDO::PARAM_STR);
                $par_x = trim($x);
                if($stmt->execute())
                {
                    if($stmt->rowCount() == 1)
                    {
                        $this->fetch = $stmt->fetch();
                        $this->from = "test_if_x_in_db";
                        return(1);
                    }
                    else
                        return(0);
                }
                unset($stmt);
            }
            unset($conn);
        }

        public function checkuser($user,$password,$hash_algo)
        {
            if($this->test_if_x_in_db("users","username",$user))
            {

                $hashed_pass = hash($hash_algo,$password);
                if(isset($this->from,$this->fetch) && $this->from == "test_if_x_in_db")
                {
                    if($this->fetch["status"] == "not verified")
                        $this->status_err = "Your account is not verified please check your email";
                    else if($this->fetch["password"] == $hashed_pass)
                        return(1);
                    else
                        $this->password_err = "Password Not Valid";   
                }
            }
            else
                $this->username_err = "No account found with that username";   
        }

        public function insert_to_db($table,$arr)
        {
            global $conn;
            $columns = array_keys($arr);
            $values  = array_values($arr);  
            $str="INSERT INTO $table (".implode(',',$columns).") VALUES ('" . implode("', '", $values) . "' )";
            $stmt = $conn->prepare($str);
            if($stmt->execute())
            {
                unset($stmt);
                unset($conn);
                return(1);
            }
  
        }
        public function update_element_in_db($table,$col,$new_value,$condition)
        {
            global $conn;
            $query = "UPDATE ". $table. " SET ". $col ." = '".  $new_value . "' WHERE " .$condition;
            $stmt = $conn->prepare($query);
            if($stmt->execute())
            {
                unset($stmt);
                unset($conn);
                return(1);
            }
  
        }
    }