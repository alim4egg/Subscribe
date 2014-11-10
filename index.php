<?
/*  Hello . This code written by Muhammad Aliff Muazzam bin Jaafar .
*   Part of REKA .
*   Feel free to checkout .
*   Built with love <3 .
*
*   http://www.facebook.com/Tester2009
*   https://github.com/alepcat1710
*   http://www.twitter.com/mambj2009
*   http://www.twitter.com/alepcat1710
*   http://kamisukagodam.blogspot.com
*   http://about.me/Tester2009
*   sulakecorporation1232@gmail.com
*   - Tester2009 -
* 
*/


// Connect .
$DBSERVER = '127.0.0.1';
$DBUSER = 'root';
$DBPASS = 'toor';
$DBNAME = 'subscribe';
$conn = new mysqli($DBSERVER, $DBUSER, $DBPASS, $DBNAME);
if ($conn->connect_error)
{
// MUST RE-CHECK TRIGGER_ERROR, DO WE NEED IT ?
trigger_error("Database connection failed: " .$conn->connect_error, E_USER_ERROR);
}

class subscribeREKA {

function userip()
// With CloudFlare reverse IP support
{
if (!empty($_SERVER['HTTP_CLIENT_IP']))
//check ip from share internet
{
$ip=$_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
//to check ip is pass from proxy//
{
$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
elseif (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
{
$ip=$_SERVER["HTTP_CF_CONNECTING_IP"];
} else {
$ip=$_SERVER['REMOTE_ADDR'];
}
return $ip;
}



}

if ( isset($_POST['email']) )
{
	$ob = new subscribeREKA();
	$email = $_POST['email'];
	$ip = $ob->userip();
	$time = date('d/m/Y h:i:s a', time());

	global $DBSERVER;
	global $DBUSER;
	global $DBPASS;
	global $DBNAME;
	global $conn;
	// should CHECK IT FIRST. the EXISTENCE .
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$error = "Invalid email !";
		return $error;
	}
	else
	{
		// lets start
		// escape special character
		$e = $conn->real_escape_string($email);
		$sql = "SELECT COUNT(*) AS TOTAL FROM users WHERE email='$e'";
		$stmt = $conn->query($sql);
		if ($stmt==false)
		{
			trigger_error("WRONG SQL: " .$sql. "Error: " .$conn->error, E_USER_ERROR);
		}
		list($total) = $stmt->fetch_row();

		if ($total > 0)
		{
			$error = "Email <b>$e</b> has been taken !";
			echo $error;
		}
		else
		{

# here where it is belong .

			$query = "SELECT * FROM users";
			$querys = $conn->query($query);
			$num_rows = $querys->num_rows;
			$count = $num_rows;
			$id = $count + 1;
			$norepemail=mt_rand();

			// Okay . now we email user about subscribtion :)
			$headers = 'From: noreply.'.$norepemail.'@aliffmuazzam.eu' . "\r\n" .'Reply-To: aliffmuazzam.eu' . "\r\n" .'X-Mailer: PHP/' . phpversion();
			$message ="Hi there! \r\rThanks for subscribe !\r\rWe will notify you if there any new product from us.\rThank you.\r\rTester2009";
			$subject ="Subscription on aliffmuazzam.eu";
			mail($e, $subject, $message, $headers);


	$sql = "INSERT INTO users (id, email, ip, time) VALUES(?, ?, ?, ?)";
	/* prepare statement */
	$stmt = $conn->prepare($sql);
	if ($stmt==false)
	{
		trigger_error("WRONG SQL: " .$sql. "Error: " .$conn->error, E_USER_ERROR);
	}
	/* bind parameters */
	$stmt->bind_param("isis", $id, $e, $ip, $time);
	/* execute */
	$stmt->execute();
	// show result
	$h = $stmt->insert_id;
	$i = $stmt->affected_rows;
	if ($h.$i==01)
	{
		echo "Subscribed ! Checkout your email.";
	}


		}

	}
}

?>

<form method="post">
<label>Put email here</label>
<input type="email" name="email">
<button type="submit">Send !</button>

<?
if ( isset($_POST['email']) )
	{
		echo "Your email is: " . $_POST['email'];
	}
?>
</form>
</body>
</html>
