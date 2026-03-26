<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title><?php echo $titulo_pag; ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">


    <!-- Favicon -->
    <link href="/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="/js/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/css/style.css?t=5" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

     <!-- Calendario -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
	<?php
		echo $html->charset();
		echo $html->meta('icon');
		echo $html->css('stilos');
		echo $this->element("scripts"); 
	?>
</head>


<body>

<div class='pagina'>

<?php //echo $this->element("encabezado"); ?>
<?php echo $this->element("menu"); ?>
<!-- <?php echo $this->element("banner"); ?> -->



 <!-- Contact Start -->
 <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 pb-4 pb-lg-0">

				<?php
				if ($session->check('Message.flash')):
						$session->flash();
				endif;
			?>
			
				<?php echo $content_for_layout;?>
                 </div>

            </div>
        </div>
    </div>
    <!-- Contact End -->
		

<?php
	/*echo $this->element("footer");*/
?>
</div>
</div>
	<?php echo $cakeDebug?>

    
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-T1JNRJFBM3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-T1JNRJFBM3');
</script>


</body>
</html>

