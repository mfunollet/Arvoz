<html>
<body>
	<h1>Troque o password para o email:  <?php echo $email1;?></h1>
	<p>Clique neste link para trocar o password: <?php echo anchor('auth/reset_password/'. $forgotten_password_code, 'Reset Your Password');?>.</p>
</body>
</html>