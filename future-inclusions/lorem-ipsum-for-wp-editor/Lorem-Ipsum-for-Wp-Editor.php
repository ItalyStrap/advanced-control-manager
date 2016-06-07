<?php
/*
* Plugin Name: Lorem Ipsum for WP Editor
* Plugin URL: http://mycodewith.wordpress.com 
* Description: Now you can test your website adding Lorem Ipsum text to your posts.
* Author: Janynne Gomes
* Version: 1.0
* Author URL: http://mycodewith.wordpress.com
Text Domain: wplg
Domain Path: /lang
*/

add_action('media_buttons_context','wplgbuttons');   

function wplgbuttons($context) {

     return $context.="<script> jQuery(function($)
                                {

                                    $( \"#selectloremipsum\" )
                                      .change(function () {                                        
                                        $( \"#selectloremipsum option:selected\" ).each(function() {                                         

                                          var loremText = new Array(
                                            \"0\",
                                            \"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris imperdiet pretium nibh at aliquam. Cras vestibulum magna vel ante tristique commodo. Maecenas hendrerit dolor sed lectus consectetur eleifend at ac lorem. Duis nisl neque, molestie in suscipit quis, dapibus eu massa. Nam ut sapien ultricies, porttitor erat a, sagittis sapien. Vestibulum tempor tempus convallis. Integer volutpat nunc in orci tincidunt tincidunt et eget nisi. Aliquam est mauris, scelerisque ut purus ut, fermentum feugiat nisl. Suspendisse placerat interdum faucibus. Aliquam erat volutpat. Fusce pulvinar purus id urna pellentesque tempor. Nunc felis odio, lobortis nec diam sed, feugiat tempus ante. Proin rutrum eros sed malesuada tristique. Sed a sodales dui. In hac habitasse platea dictumst. In neque mi, mattis a commodo nec, malesuada ut nibh.</p>\", 
                                            \"<p>Pellentesque suscipit nibh eu odio hendrerit rutrum. Duis vehicula est ac bibendum luctus. Ut consectetur vel diam commodo porttitor. Nam accumsan ligula vitae lacus dictum venenatis. Maecenas congue sollicitudin augue, ac lacinia enim laoreet et. In sed condimentum magna. Maecenas hendrerit nunc magna, vel faucibus lacus iaculis in. Donec aliquet urna mauris. Sed semper mauris eget magna tempus vestibulum. Praesent luctus dictum lacus quis rutrum. Nam malesuada velit at gravida sodales. Aliquam ut iaculis urna, vitae interdum odio. Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur tincidunt mauris sed auctor sollicitudin.</p>\", 
                                            \"<p>Curabitur id lacus felis. Sed justo mauris, auctor eget tellus nec, pellentesque varius mauris. Sed eu congue nulla, et tincidunt justo. Aliquam semper faucibus odio id varius. Suspendisse varius laoreet sodales. Etiam dignissim consequat odio gravida auctor. Mauris ut blandit nulla. Aenean sed lacinia dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>\",
                                            \"<p>Phasellus vitae vestibulum felis. Suspendisse placerat eros magna, ac fringilla nisi convallis vel. Cras vel erat sit amet odio suscipit porttitor. Nunc a orci cursus, blandit ligula ut, fermentum nisi. Aliquam erat volutpat. Nullam adipiscing tempor mi sit amet ullamcorper. Integer quam justo, accumsan et semper eu, consectetur vel arcu. Nulla facilisis felis vitae erat dictum, commodo cursus nisi pulvinar. Vestibulum id iaculis velit, nec eleifend odio. In sed adipiscing diam.</p>\");

                                    
                                       if($( this ).val() != '0') {
                                            for(i=1; i<= $( this ).val(); i++)
                                            {
                                              $('#content_ifr').contents().find('body').append( loremText[i]);
                                            }
                                        }
                                      });

                                      })
                                      .change();
                                  
                                       
                                });            
                                             
                       </script>

                       <select id=\"selectloremipsum\" name=\"selectloremipsum\">
                            <option value=\"0\">Paste Lorem Ipsum</option>
                            <option value=\"1\">Insert 1 paragraph</option>
                            <option value=\"2\">Insert 2 paragraph</option>
                            <option value=\"3\">Insert 3 paragraph</option>
                            <option value=\"4\">Insert 4 paragraph</option>
                        </select>";
}

?>