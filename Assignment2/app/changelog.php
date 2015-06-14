<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
<head>
<link rel="stylesheet" type="text/css" href="style_bib.css"/>
</head>
<body>
	<h2 style="text-align:center;">Changelog</h2>
	<?php show_changelog();?>
	<a href="index.php">Back to App home</a>
	<br>
</body>
</html>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="createBibtex.js"></script>
<?php

function show_changelog() {

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://127.0.0.1:5984/books/_changes?descending(true)'
	));
	
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
	json_to_html_table($resp);

}

function json_to_html_table($json){
	echo "<br>";
	$data =  json_decode($json);
	$json_books = $data -> results;
	
	for ($i = 0; $i <= 10; $i++) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://127.0.0.1:5984/books/'. $json_books[$i]->id
		));
	
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		json_to_html_table($resp);
		// Close request to clear up some resources
		curl_close($curl);
    		print_r($resp);
	} 

	//removes duplicate results
	$books=[];
	foreach($json_books as $book){
		$books[$book->id] = $book->value;
	}

	print_table_headers();
	foreach($books as $id=>$book){
		$attachment_list = get_attachment_list($book);
		$authors = get_author_array($book);

		echo "<tr>";	
		$book_safe_json = str_replace('"',"'",json_encode($book));
		echo '<td><a onclick="return createBibtex('.$book_safe_json.');" href="javascript:void(0)">'.$book->title."</a></td>";
		echo "<td>".implode(", ",$authors)."</td>";
		echo "<td>".$book->year."</td>";
		echo "<td>".$book->source."</td>";
		echo "<td>".$attachment_list."</td>";
		echo '<td align="center"><a href="edit_form.php?id='.$id.'"><img src="icons/edit.png"></a></td>';
		echo '<td align="center"><a href=handle_delete.php?id='.$id.'><img src="icons/delete.png"></a></td>';
		echo '<td align="center"><a href=http://127.0.0.1:5984/books_app/handle_upload/handle_upload.html?doc_id='.urlencode($id)."&doc_rev=".urlencode($book->_rev).' target="_blank"><img src="icons/pdf.png"></a></td>';
		echo "</tr>";	
	}
	echo "</table>";
}

?>
