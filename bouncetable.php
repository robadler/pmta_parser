<?php

function build_row($row)
{
	$shit = <<< HTML
			<tr class="cursor-pointer ussr-component-collection-row">
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper"><?php echo(strtotime($row[delivered])); ?></span>
				</td>
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper"><?php echo(strtotime($row[queued])); ?></span>
				</td>
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper">$row[recipient]</span>
				</td>
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper">$row[dsnstatus]</span>
				</td>
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper">$row[bouncereason]</span>
				</td>
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper">$row[acctid]</span>
				</td>
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper">$row[contactid]</span>
				</td>
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper">$row[msgid]</span>
				</td>
				<td class="ussr-component-collection-cell ussr-component-collection-cell-type-text">
					<span class="ussr-component-collection-cell-data-wrapper">$row[seqid]</span>
				</td>
			</tr>
HTML;

	return $shit;
}

function build_table($noentries=100,$page=0,$sort='delivered',$order='ASC') {
	$table = '';
	$range = $noentries * $page;
	$link = mysqli_connect(DBSERVER, DBUSER, DBPASS, DBNAME);

	$query = "SELECT * FROM ". DBTABLE ." ORDER BY $sort $order LIMIT $range, $noentries";

	$result = mysqli_query($link, $query) or die("Error querying database." . mysqli_error($link));
	while ($row = mysqli_fetch_assoc($result))
	{
        $table .= build_row($row);
    }
    return $table;
}