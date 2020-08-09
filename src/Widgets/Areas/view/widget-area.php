<div <?php $this->attr( $sidebar_id . '_widget_area', $widget_area_attr, true ) ?>>
	<div <?php $this->attr( $sidebar_id . '_container', array( 'class' => $container_width ), true ) ?>>
		<div <?php $this->attr( $sidebar_id . '_row', array( 'class' => 'row' ), true ) ?>>
			<?php dynamic_sidebar( $sidebar_id ); ?>
		</div>
	</div>
</div>
