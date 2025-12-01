<?php
// Initialize variables for the result message and the input text.
$checkResult = "<span style='color:red'> Try the magic word 'fred' ....</span>";
$myInputText01 = '';

// --- Shell Command Execution for Environment Check ---

// Helper function to safely execute a command and return output or an error message
function runCommandCheck(string $command): string {
    // 2>&1 redirects stderr to stdout, so we capture both success and error output
    $output = shell_exec($command . ' 2>&1');

    if ($output === null) {
        // shell_exec returns null if an error occurs (like the command not being found)
        return "Command failed or not found: $command";
    }

    $trimmedOutput = trim($output);

    if (empty($trimmedOutput)) {
        // If execution was successful but returned no output (unlikely for --version)
        return "Command executed, but returned no visible output.";
    }

    return $trimmedOutput;
}

// Check Node.js version
$nodeVersion = runCommandCheck('node --version');

// Check Python version
$pythonVersion = runCommandCheck('python --version');

// --- End of Shell Command Execution ---


// Check if the form was submitted (i.e., if the request method is POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input text from the form data.
    $myInputText01 = $_POST['myText01'] ?? '';

    // The magic word logic
    $myCheck = false;
    if ($myInputText01 === 'fred') {
        $myCheck = true;
    }

    // Determine the result message and color
    if ($myCheck) {
        $checkResult = "<b style='color:green'> Cool! The magic word works! </b>";
    } else {
        $checkResult = "<span style='color:red'> Try the magic word 'fred' ....</span>";
    }
}

// Now, render the HTML page, embedding the dynamic content.
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Environment Capabilities Check v2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h3, h4 {
            text-align: center;
            color: #333;
        }
        form {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 50%;
        }
        input[type="submit"] {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .results-box {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            margin-top: 20px;
            word-wrap: break-word;
        }
        .results-box p {
            margin: 5px 0;
            padding: 5px;
            border-bottom: 1px dotted #adb5bd;
        }
        .results-box p:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>PHP Environment Capabilities Check v2</h3>

        <form action="" method="post" >
            <label for="myText01">Magic Word Check:</label>
            <!-- Note: I removed the value="<?= htmlspecialchars($myInputText01); ?>" to force re-entry, as per the original file's commented state. -->
            <input type="text" id="myText01" name="myText01" value="">
            <input type="submit" value="Check">
        </form>
        
        <div style="text-align: center; margin-bottom: 20px;">
            <?php echo $checkResult; ?>
        </div>

        <h4>Environment Shell Output</h4>
        <div class="results-box">
            <p><strong>Node.js Check:</strong> <code><?php echo htmlspecialchars($nodeVersion); ?></code></p>
            <p><strong>Python Check:</strong> <code><?php echo htmlspecialchars($pythonVersion); ?></code></p>
            <p style="font-size: 0.8em; color: #6c757d;">(A successful check displays the version number. If it displays "Command failed or not found," the language is likely unavailable on this host.)</p>
        </div>
    </div>
</body>
</html>
