<?php
class pageInterface {
	///
	/// Properties
	///

	/**
	 * Page title
	 * @var string
	 */
	public $title;

	///
	/// Functions
	///

	/**
	 * Initializes a new instance of the csv2QuickStatements class
	 *
	 * @param array $csv_data the data from the CSV
	 */
	public function __construct ($title="") {
		$this->title	= $title;
	}

	public function alert ($message,$class="info") {

		switch ($class) {
			case 'success':
				$header = "Success!";
				break;
			case 'warning':
				$header = "Warning:";
				break;
			case 'danger':
				$header = "Error:";
				break;
			default:
				$class = "info";
				$header = "Info:";
				break;
		}

		echo '<div class="alert alert-'.$class.'" role="alert"><strong>'.$header.'</strong> '.$message.'</div>';

	}

}