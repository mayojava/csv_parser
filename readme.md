# CSV Parser
PHP CSV parser that can convert a csv file to an Object and also
converts an json object array to csv

## Sample Usage
* To use, call `Parser.parser($filePath, $outputFilePath)`. The `outputFilePath`
parameter is optional. If not passed, output file will be saved in
`./output/output.json`

## Sample input file
Input file should either be `.csv file` or a `.json`. Exception is thrown if
file is not in this format or contains invalid format in row.

**sampleinput.csv**
```
day:Monday,food:rice,amount:200
day:Tuesday,food:beans,amount:400
day:Friday,food:yam,amount:50
```

**sampleinput.json**
```
[
    {
        "club_name":"Real Madrid",
        "color":"White",
        "country":"Spain"
    },
    {
        "club_name":"Chelsea",
        "color":"Blue",
        "country":"England"
    },
    {
        "club_name":"Bayern Munich",
        "color":"Red",
        "country":"Germany"
    }
]
```

## Testing
Navigate to project root directory from console and type this command:
`php csvparsertest.php`
Output is generated in the `output` folder.
