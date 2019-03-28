<!-- HEADER -->
<?php 
	include("includes/includedFiles.php");
?>

<!-- START MAIN CONTENT -->
<h1 class="pageHeadingBig">You Might also like</h1>
<div class="gridViewContainer">
	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

		while($row = mysqli_fetch_array($albumQuery)) {
		?>
			<div class="gridViewItem">
				<span role="link" onclick="openPage('<?php echo 'album.php?id=' . $row['id']; ?>')">
					<img src="<?php echo $row['artworkPath']; ?>" alt="<?php echo $row['title']; ?>">
					<div class="gridViewInfo">
						<?php echo $row['title']; ?>
					</div>
				</span>
			</div>
		<?php
		}
	?>
</div>
<!-- END MAIN CONTENT -->

					
