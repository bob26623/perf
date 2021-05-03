<?php
        session_start();
        if($_SESSION["Username"] == "")
        {
                header("location:logout.php");
                exit();
        }

        // set timeout period in seconds
        $inactive = 1800;

        // check to see if $_SESSION['timeout'] is set
        if(isset($_SESSION['timeout']) ) {
                $session_life = time() - $_SESSION['timeout'];
                if($session_life > $inactive)
                        { session_destroy(); header("Location: logout.php"); }
        }
        $_SESSION['timeout'] = time();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<head>
<title>Performance Reporting Tool</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
</head>


<body id="top">
<div class="wrapper col1">
</div>
<div class="wrapper col2">
  <div id="header">
    <div id="logo">
      <h1>G-able</h1>
      <p>To be the trusted business partner</p>
    </div>
    <ul id="topnav">
      <li class="last"><a href="#">Link Text</a></li>
      <li><a href="#">DropDown</a>
        <ul>
          <li><a href="#">Link 1</a></li>
          <li><a href="#">Link 2</a></li>
          <li><a href="#">Link 3</a></li>
        </ul>
      </li>
      <li><a href="full-width.html">Full Width</a></li>
      <li><a href="style-demo.html">Style Demo</a></li>
      <li class="active"><a href="index.html">Home</a></li>
    </ul>
    <br class="clear" />
  </div>
</div>
<div class="wrapper col3">
  <div id="gallery">
    <ul>
      <li class="placeholder" style="background-image:url(images/gallery/default.gif);">Image Holder</li>
      <li><a class="swap" href="#"><img src="images/gallery/1_s.gif" alt="" /><span><img src="images/gallery/1.gif" width="950" height="370" alt="" /></span></a></li>
      <li><a class="swap" href="#"><img src="images/gallery/2_s.gif" alt="" /><span><img src="images/gallery/2.gif" width="950" height="370" alt="" /></span></a></li>
      <li><a class="swap" href="#"><img src="images/gallery/3_s.gif" alt="" /><span><img src="images/gallery/3.gif" width="950" height="370" alt="" /></span></a></li>
      <li><a class="swap" href="#"><img src="images/gallery/4_s.gif" alt="" /><span><img src="images/gallery/4.gif" width="950" height="370" alt="" /></span></a></li>
      <li class="last"><a class="swap" href="#"><img src="images/gallery/5_s.gif" alt="" /><span><img src="images/gallery/5.gif" width="950" height="370" alt="" /></span></a></li>
    </ul>
  </div>
</div>
<div class="wrapper col4">
  <div id="services">
    <ul>
      <li><a href="#"><strong>Weddings</strong><img src="images/demo/234x210.gif" alt="" /></a></li>
      <li><a href="#"><strong>Corporate</strong><img src="images/demo/234x210.gif" alt="" /></a></li>
      <li><a href="#"><strong>Lifestyle</strong><img src="images/demo/234x210.gif" alt="" /></a></li>
      <li class="last"><a href="#"><strong>Events</strong><img src="images/demo/234x210.gif" alt="" /></a></li>
      <li><a href="#"><strong>Artistic</strong><img src="images/demo/234x210.gif" alt="" /></a></li>
      <li><a href="#"><strong>Infants</strong><img src="images/demo/234x210.gif" alt="" /></a></li>
      <li><a href="#"><strong>Architecture</strong><img src="images/demo/234x210.gif" alt="" /></a></li>
      <li class="last"><a href="#"><strong>Black &amp; White</strong><img src="images/demo/234x210.gif" alt="" /></a></li>
    </ul>
    <br class="clear" />
  </div>
</div>
<div class="wrapper col5">
  <div id="container">
    <div id="content">
      <h2>About This Free CSS Template</h2>
      <p>This is a W3C standards compliant Open Source free CSS template from <a href="http://www.os-templates.com/">OS Templates</a>.</p>
      <p>This template is distributed under a <a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-Share Alike 3.0 Unported License</a>, which allows you to use and modify the template for both personal and commercial use when you keep the provided credit links in the footer.</p>
      <p>For more free css templates visit <a href="http://www.os-templates.com/">Open Source Templates</a>.</p>
      <p>Lacusenim inte trices lorem anterdum nam sente vivamus quis fauctor mauris. Wisinon vivamus wisis adipis laorem lobortis curabiturpiscingilla dui platea ipsum lacingilla.</p>
      <p>Semalique tor sempus vestibulum libero nibh pretium eget eu elit montes. Sedsemporttis sit intesque felit quis elis et cursuspenatibulum tincidunt non curabitae.</p>
    </div>
    <div id="column">
      <div class="flickrbox">
        <h2 class="title">Flickr</h2>
        <ul>
          <li><a href="#"><img src="images/demo/80x80.gif" alt="" /></a></li>
          <li><a href="#"><img src="images/demo/80x80.gif" alt="" /></a></li>
          <li class="last"><a href="#"><img src="images/demo/80x80.gif" alt="" /></a></li>
          <li><a href="#"><img src="images/demo/80x80.gif" alt="" /></a></li>
          <li><a href="#"><img src="images/demo/80x80.gif" alt="" /></a></li>
          <li class="last"><a href="#"><img src="images/demo/80x80.gif" alt="" /></a></li>
        </ul>
        <br class="clear" />
      </div>
    </div>
    <br class="clear" />
  </div>
</div>
<div class="wrapper col6">
  <div id="footer">
    <div id="contactform">
      <h2>Why Not Contact Us Today !</h2>
      <form action="#" method="post">
        <fieldset>
        <legend>Contact Form</legend>
        <label for="fullname">Name:
        <input id="fullname" name="fullname" type="text" value="" />
        </label>
        <label for="emailaddress" class="margin">Email:
        <input id="emailaddress" name="emailaddress" type="text" value="" />
        </label>
        <label for="message">Message:<br />
        <textarea id="message" name="message" cols="40" rows="4"></textarea>
        </label>
        <p>
          <input id="submitform" name="submitform" type="submit" value="Submit" />
          &nbsp;
          <input id="resetform" name="resetform" type="reset" value="Reset" />
        </p>
        </fieldset>
      </form>
    </div>
    <!-- End Contact Form -->
    <div id="compdetails">
      <div id="officialdetails">
        <h2>Company Information !</h2>
        <ul>
          <li>Copyright &copy; 2009 - All Rights Reserved</li>
          <li>Company Name Ltd</li>
          <li>Registered in England &amp; Wales</li>
          <li>Company No. xxxxxxx</li>
          <li class="last">VAT No. xxxxxxxxx</li>
        </ul>
        <h2>Stay in The Know !</h2>
        <p><a href="#">Get Our E-Newsletter</a> | <a href="#">Grab The RSS Feed</a></p>
      </div>
      <div id="contactdetails">
        <h2>Our Contact Details !</h2>
        <ul>
          <li>Company Name</li>
          <li>Street Name &amp; Number</li>
          <li>Town</li>
          <li>Postcode/Zip</li>
          <li>Tel: xxxxx xxxxxxxxxx</li>
          <li>Fax: xxxxx xxxxxxxxxx</li>
          <li>Email: info@domain.com</li>
          <li class="last">LinkedIn: <a href="#">Company Profile</a></li>
        </ul>
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<div class="wrapper col7">
  <div id="copyright">
    <ul>
      <li><a href="#">Online Privacy Policy</a></li>
      <li><a href="#">Terms of Use</a></li>
      <li><a href="#">Permissions &amp; Trademarks</a></li>
      <li><a href="#">Product License Agreements</a></li>
      <li class="last">Template by <a target="_blank" href="http://www.os-templates.com/" title="Open Source Templates">OS Templates</a></li>
    </ul>
  </div>
</div>
</body>
</html>
