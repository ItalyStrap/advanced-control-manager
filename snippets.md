# Sidebars

```
add_filter ( 'dynamic_sidebar_params' , 'widget_params');

function widget_params( $params ) {

	/**
	 * Display the sidebars parameters
	 * @link https://developer.wordpress.org/reference/hooks/dynamic_sidebar_params/
	 */
	var_dump( $params );

	return $params;
	
}
```