<?php 
   $redis = new Redis();
	$redis->connect('redis', 6379);
	// $redis->auth('REDIS_PASSWORD');

	// string
	// serialize là cách mã hóa dữ liệu
	$array =  ['content' => "Phương", 'done'=>"done", 'id'=>1];
	$redis->set("key_test", serialize($array));
	$vl = $redis->get("key_test");
	$redis->expire("key_test", 10); // thời gian hiệu lực của từng giây
	// var_dump(unserialize($vl))."</br>";

	// list (key, vulue...)
	$redis->rpush("languages", "french", "con gà", 'con vịt'); 
	$list = $redis->get("languages");
	// echo "list: ";
	// print_r($list);
	echo "</br>";
	// LPUSH: adds an element to the beginning of a list
	// RPUSH: add an element to the end of a list
	// LPOP: removes the first element from a list and returns it
	// RPOP: removes the last element from a list and returns it
	// LLEN: gets the length of a list
	// LRANGE: gets a range of elements from a list

	// hash
	$redis->hset('hash', 'la', 44);
	$redis->hmset('hash', [
	    'age' => 44,
	    'country' => 'finland',
	    'occupation' => 'software engineer',
	    'reknown' => 'linux kernel',
	]);
	$data = $redis->get('hash');
	// print_r($data);
	echo "</br>";

	// all key
	$allKeys = $redis->keys('*');
	// print_r($allKeys);
	echo "</br>";

	// giảm hoặc tăng giá trị
	$redis->set("counter", 0);
	$redis->incrby("counter", 15); // 15 giá trị tăng thêm +15
	$redis->incrby("counter", 5);  // 20 giá trị tăng thêm +5
	$redis->decrby("counter", 10); // 10 giá trị giảm
	$counter = $redis->get('counter');
	// print_r($counter);
	
	// $redis->rpush("mylist2c", "a1");
	// $redis->rpush("mylist2c", "a2");
	// $redis->rpush("mylist2c", "a3");
	// $redis->rpush("mylist2c", "a4");
	// $redis->rpush("mylist2c", "a5");
	// $redis->rpush("mylist2c", "a6");
	$value = $redis->lpop('mylist2c');
	echo $value;
?>
