<?php if (isset($this->Paginator)): ?>
<div class="pagination">
	<ul>
	    <?php
	    	echo $this->Paginator->numbers(array('separator' => '','tag'=>'li','currentClass'=>'active','currentTag'=>'a'));
	    ?>
	</ul>
</div>
<?php endif; ?>