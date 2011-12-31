<html>
    <head>
        <title>Karma Email</title>
    </head>
    <body style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;">

        <!-- START outer table (wrap) -->
        <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
            <tr>
                <td bgcolor="#A5A6A4">

                    <!-- START top bar -->
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" height="40">
                        <tr>
                            <td bgcolor="#2B3137" align="center">
                                <p style="font-family:Arial, Helvetica, sans-serif;font-size:11px;color:#DDDDDD;">Email not displaying correctly? <a href="#" style="color:#999999;">View the online version</a></p>
                            </td>
                        </tr>
                    </table>
                    <!-- END top bar -->

                    <!-- START top spacer -->
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" height="65">
                        <tr><td bgcolor="#A5A6A4"></td></tr>
                    </table>
                    <!-- END top spacer -->

                    <!-- START main layout top -->
                    <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr><td width="600" height="12" bgcolor="#2B3137"></td></tr>
                        <tr><td width="600" height="98"><img src="http://themes.truethemes.net/Karma-Email/images/grey/layout-main-header.jpg" width="600" height="98" alt="main header image"></td></tr>
                        <tr><td width="600" height="42"><img src="http://themes.truethemes.net/Karma-Email/images/_global/layout-gradient-top.jpg" width="600" height="42" alt="subtle gradient image"></td></tr>
                    </table>
                    <!-- END main layout top -->

                    <!-- START main layout content (wrap) -->
                    <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td bgcolor="#F4F4F2">

                            <!-- START main content -->
                            <table width="530" border="0" cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                    <td>
                                        <!-- START MODULE - full width image -->
                                        <table width="530" border="0" cellpadding="0" cellspacing="0">
                                            <tr><td valign="top"><img src="http://files.truethemes.net/emails/karma-email/530x205.jpg" width="530" height="205"></td></tr>
                                        </table>
                                        <!-- END MODULE - full width image -->


                                        <!-- START MODULE - callout text -->
                                        <table width="530" border="0" cellpadding="0" cellspacing="0">
                                            <tr><td><img src="http://themes.truethemes.net/Karma-Email/images/_global/content-divider-normal.jpg" width="530" height="33" alt=""></td></tr>
                                            <tr><td><p style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#000000;line-height:23px">Donec ullamcorper nulla non metus auctor fringilla. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</p></td></tr>
                                            <tr><td><img src="http://themes.truethemes.net/Karma-Email/images/_global/content-divider-normal.jpg" width="530" height="33" alt=""></td></tr>
                                        </table>
                                        <!-- END MODULE - callout text -->

                                        <!-- START MODULE - small image left -->
                                        <?php foreach ($data as $title => $_data): ?>
                                        <img src="http://themes.truethemes.net/Karma-Email/images/_global/spacer.gif" width="10" height="20">
                                        <h3><?php echo Helper::filter($title); ?></h3>
                                        <table width="530" border="0" cellpadding="0" cellspacing="0">
                                        <?php foreach ($_data as $item): ?>
                                            <tr>
                                                <td width="170" valign="top"><img src="<?php echo URL::site($this->photo($item, '170x210')); ?>" width="170" height="210" /></td>
                                                <td width="30"></td>
                                                <td width="330" valign="top">
                                                    <p style="font-family:Arial, Helvetica, sans-serif;font-size:18px;color:#000000;font-weight:bold;padding:0px;margin:0px 0px 10px 0px;"><?php echo Helper::filter($item->title) ?></p>
                                                    <p style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#000000;line-height:20px"><?php echo Helper::filter($item->description) ?></p>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                        </table>
                                        <?php endforeach ?>
                                        <!-- END MODULE - small image left -->
                                    </td>
                                </tr>
                            </table>
                            <!-- END main content -->
                            </td>
                        </tr>
                    </table>
                    <!-- END main layout content (wrap) -->

                    <!-- START main layout bottom -->
                    <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr><td width="600" height="41"><img src="http://themes.truethemes.net/Karma-Email/images/_global/layout-gradient-bottom.jpg" width="600" height="41" alt="subtle gradient image"></td></tr>
                        <tr><td width="600" height="10" bgcolor="#2B3137"></td></tr>
                        <tr><td width="600" height="98"><img src="http://themes.truethemes.net/Karma-Email/images/grey/layout-footer.jpg" width="600" height="98" alt="bottom footer image"></td></tr>
                    </table>
                    <!-- END main layout bottom -->

                    <!-- START bottom spacer -->
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" height="65">
                        <tr><td bgcolor="#A5A6A4"></td></tr>
                    </table>
                    <!-- END bottom spacer -->

                    <!-- START footer (wrap)-->
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" height="250">
                        <tr>
                            <td bgcolor="#2B3137">
                                  <!-- START footer content -->
                                  <table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
                                      <tr>
                                          <td valign="top">
                                              <p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#FFFFFF;font-weight:bold;padding:0px;margin:0px 0px 10px 0px;">Connect With Us:</p>
                                              <p style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#FFFFFF;font-weight:bold;padding:0px;margin:0px 0px 30px 0px;">
                                                  <a href="#" style="padding:0px;margin:0px 7px 0px 0px;"><img src ="http://themes.truethemes.net/Karma-Email/images/_global/social-rss.png" width="25" height="25" border="0"></a>
                                                  <a href="#" style="padding:0px;margin:0px 7px 0px 0px;"><img src ="http://themes.truethemes.net/Karma-Email/images/_global/social-twitter.png" width="25" height="25" border="0"></a>
                                                  <a href="#" style="padding:0px;margin:0px 7px 0px 0px;"><img src ="http://themes.truethemes.net/Karma-Email/images/_global/social-facebook.png" width="25" height="25" border="0"></a>
                                                  <a href="#" style="padding:0px;margin:0px 7px 0px 0px;"><img src ="http://themes.truethemes.net/Karma-Email/images/_global/social-flickr.png" width="25" height="25" border="0"></a>
                                                  <a href="#" style="padding:0px;margin:0px 7px 0px 0px;"><img src ="http://themes.truethemes.net/Karma-Email/images/_global/social-youtube.png" width="25" height="25" border="0"></a>
                                                  <a href="#" style="padding:0px;margin:0px 7px 0px 0px;"><img src ="http://themes.truethemes.net/Karma-Email/images/_global/social-delicious.png" width="25" height="25" border="0"></a>
                                                  <a href="#" style="padding:0px;margin:0px 7px 0px 0px;"><img src ="http://themes.truethemes.net/Karma-Email/images/_global/social-linkedin.png" width="25" height="25" border="0"></a>
                                              </p>
                                              <p style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#FFFFFF;padding:0px;margin:0px 0px 20px 0px;""><a href="#" style="color:#999999;">Forward this email to a friend.</a></p>
                                              <p style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#FFFFFF;padding:0px;margin:0px 0px 15px 0px;">You have received this email because you joined our mailing list on our website. <a href="#" style="color:#999999;">unsubscribe</a></p>
                                          </td>
                                      </tr>
                                  </table>
                                  <!-- END footer content -->
                            </td>
                        </tr>
                    </table>
                    <!-- END footer (wrap) -->
                </td>
            </tr>
        </table>
        <!-- END outer table (wrap) -->
    </body>
</html>