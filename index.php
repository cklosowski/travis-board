<?php
ob_start();
$endpoint = 'https://api.travis-ci.org/repos/easydigitaldownloads/Easy-Digital-Downloads/builds';
$api_response = file_get_contents( $endpoint );
$results = json_decode( $api_response, true );
?><html><head></head><body><table id="builds"><?php
foreach ( $results as $result ) {
	switch( $result['event_type'] ) {
		case 'push':
		default:
			$type_icon = 'push-icon.png';
			break;
		case 'pull_request':
			$type_icon = 'pull-request.png';
			break;
	}

	switch( $result['state'] ) {
		case 'started':
			$icon  = '&#9719;';
			$color = '#FFD700';
			break;
		case 'finished':
			if ( $result['result'] == 1 ) {
				$icon  = '&#10007;';
				$color = '#800000';
			} elseif ( $result['result'] === 0 ) {
				$icon  = '&#10004;';
				$color = '#008000';
			} elseif ( $result['result'] == NULL ) {
				$icon  = '&#8861;';
				$color = '#D3D3D3';
			}
			break;
		default:
			$icon  = '&#8861;';
			$color = '#D3D3D3';
			break;
	}

	if ( $result['state'] == 'finished' ) {
		$to_time = strtotime( $result['finished_at'] );
		$from_time = strtotime( $result['started_at'] );
		$duration = round( abs( $to_time - $from_time ) / 60,2 ) . 'min';
	} elseif ($result['state'] == 'started' ) {
		$to_time = time();
		$from_time = strtotime( $result['started_at'] );
		$duration = round( abs( $to_time - $from_time ) /60,2 ) . 'min <img src="/images/running.png" height="18px" width="18px"/>';
	} else {
		$duration = 'Created & Waiting';
	}
	?>
        <tr>
                <td style="font-size: 24px; width: 100px;" class="projectName"><?php echo $result['number']; ?></td>
                <td style="font-size: 24px;" class="projectBranch"><?php echo $result['branch']; ?></td>
		<td style="font-size: 24px; width: 45px;" class="projectType">
			<img src="/images/<?php echo $type_icon; ?>" />
		</td>
		<td style="font-size: 24px;" class="projectDuration"><?php echo $duration; ?></td>
                <td style="font-size: 48px; font-weight: 800; width: 50px;" class="projectsStatus">
			<span style="color: <?php echo $color; ?>"><?php echo $icon; ?></span>
                </td>
        </tr>
	<tr style="font-size: 11px;"><td colspan="5" class="projectMessage"><?php echo $result['message']; ?></td></tr>
	<?php
}

?></table></body></html><?php
