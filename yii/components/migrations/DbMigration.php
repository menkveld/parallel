<?php
namespace parallel\yii\components\migrations;


/**
 * This is a base class for DB Migrations. It adds assitional features that are useful like
 * the ability to execute .sql scripts, rather than use the Yii database api. You can thus
 * export sql scripts from MySQL Workbench and execute them with a migration.
 * 
 * @author Anton R Menkveld
 *
 */
class DbMigration extends \CDbMigration {

	/**
	 * This method will execute the given sql script
	 * 
	 * @param unknown_type $filePath
	 * @throws \CException
	 */
	public function executeSQLScript($filePath) {
		$time=microtime(true);
		$file = new TXFile(array(
				'path' => $filePath,
		));
	
		if (!$file->exists)
			throw new \CException($filePath." does not exist!");
			
		try {
			if ($file->open(TXFile::READ) === false)
				throw new \CException("Can't open '$filePath'");
	
			$total = floor($file->size / 1024);
			
			$sql = ''; // To hold each SQL query
			while (!$file->endOfFile()) {
				$line = $file->readLine();
				$line = trim($line);
				// Ignore comments starting with -- and empty lines
				if(substr($line, 0, 2) != '--' && !empty($line)) {
					$sql .= ' '.$line;
				}
				// Execute only when line ends with ;
				if(substr($line, -1) == ';') {
					//echo "\nExecuting: ".$sql."\n";
					// Execute SQL command
					$this->getDbConnection()->createCommand($sql)->execute();
					// Reset the sql query
					$sql = "";
				}
			
				$current = floor($file->tell() / 1024);
			}
			// Close the file
			$file->close();
		} catch (Exception $e) {
			// Something went wrong
			$file->close();
				
			// Raise the exception
			throw $e;
		}
		echo "\n     Executed ".$filePath;
		echo "           (time: ".sprintf('%.3f', microtime(true)-$time)."s)\n\n";
		return true;	// If we got this far - all was well
	}
}