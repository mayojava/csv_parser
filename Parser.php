<?php
    class Parser {
        /**
        * receives the path to file to be parsed
        * uses the file extension to determine if file is to be
        * converted to a csv output file or a json output file
        *
        * @param $filepath - path of the file
        **/
        public static function parse($filepath, $outputfilename = null)
        {
            $fileExtension = explode('.', $filepath);
            $fileExtension = $fileExtension[1];

            switch ($fileExtension) {
               case 'csv':
                   self::toJsonObjectFile($filepath, $outputfilename);
                   break;
               case 'json':
                   self::toCsvFile($filepath, $outputfilename);
                   break;
               default:
                   throw new Exception('invalid input file format');
           }
        }

        /**
        * @param $jsonfile - json object containing the rows as an array
        * @param $outputfilename - output file name {OPTIONAL}
        **/
        private static function toCsvFile($jsonfile, $outputfilename = null)
        {
            $rowContents = file_get_contents($jsonfile);
            $rowContents = json_decode($rowContents);

            $outputfilename = !empty($outputfilename) ?
                                $outputfilename :
                                './output/output.csv';
            self::deleteFileIfExists($outputfilename);

            if (is_array($rowContents)) {
                foreach ($rowContents as $csvRow) {
                    $fieldCount  = 0;

                    foreach ($csvRow as $key => $value) {
                        /** check if value contains a comma
                        * escape if it does
                        **/
                        if (strpos($value, ',') !== false) {
                            $value = '"' . $value . '"';
                        }

                        if ($fieldCount == 0) {
                            self::writeToFile($key . ':' . $value,
                                    $outputfilename);
                        } else {
                            self::writeToFile(',' . $key. ':' . $value,
                                    $outputfilename);
                        }

                        $fieldCount++;
                    }

                    self::writeToFile(PHP_EOL, $outputfilename);
                }
            } else {
                throw new Exception('invalid input file format');
            }
        }

        /**
        * reads in a csv file and converts the csv rows to objects
        *
        * @param $jsonfile - path to csv file
        * @param $outputfilename - output file name {OPTIONAL}
        **/
        private static function toJsonObjectFile($csvfile,
                                    $outputfilename = null)
        {
            $result = [];

            $outputfilePath = !empty($outputfilename) ?
                                $outputfilename :
                                './output/output.json';
            self::deleteFileIfExists($outputfilePath);

            self::writeToFile('[', $outputfilePath);
            self::writeToFile(PHP_EOL, $outputfilePath);

            $rowCount  = 0;

            if (($handle = fopen($csvfile, 'r')) != false) {
                while (($rowArray = fgetcsv($handle, 0, ',')) != false) {
                    $rowObject = new stdClass();

                    foreach ($rowArray as $value) {
                        $keyValue = explode(':', $value);

                        if (count($keyValue) != 2) {
                            self::throwInvalidFieldInRowException(++$rowCount);
                        }

                        if (empty($keyValue[0])) {
                            self::throwInvalidFieldInRowException(++$rowCount);
                        }

                        $keyValue[0] = trim($keyValue[0]);
                        $rowObject->$keyValue[0] = $keyValue[1];
                    }

                    if ($rowCount == 0) {
                        self::writeToFile(json_encode($rowObject),
                                $outputfilePath);
                    } else {
                        self::writeToFile(','.PHP_EOL, $outputfilePath);
                        self::writeToFile(json_encode($rowObject),
                                $outputfilePath);
                    }

                    $rowCount++;
                }

                self::writeToFile(PHP_EOL, $outputfilePath);
                self::writeToFile(']', $outputfilePath);

                fclose($handle);
            }
        }

        /**
        * writes the output to the path specified by filename
        *
        * @param $output - output string
        * @param $filename - file path of generated output file
        **/
        private static function writeToFile($output, $filename)
        {
            $handle = fopen($filename, 'a') or
                die('unable to create output file');
            fwrite($handle, $output);
            fclose($handle);
        }

        /**
        * throw exception for invalid format found in row
        * $row - row with invalid field
        **/
        private static function throwInvalidFieldInRowException($row)
        {
            throw new Exception("Invalid field in row $row");
        }

        /**
        * deletes file it it already exists in path
        * @param $path - file path
        **/
        private static function deleteFileIfExists($path)
        {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
