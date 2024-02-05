// Function to download code
function downloadCode() {
    const htmlContent = document.getElementById('htmlCode').value;
    const cssContent = document.getElementById('cssCode').value;
    const jsContent = document.getElementById('jsCode').value;

    // JavaScript form validation
    if (!validateHTML(htmlContent)) {
        return; // Prevent form submission if HTML validation fails
    }

    // Use relative path for the fetch command
    fetch('./save-code.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ htmlContent, cssContent, jsContent }),
    })
    .then(response => response.json())
    .then(data => {
        // Assuming the backend returns the generated zip file path
        window.location.href = data.zipFilePath;
    })
    .catch(error => console.error('Error:', error));
}

// HTML validation function
function validateHTML(htmlContent) {
    const openingTagIndex = htmlContent.indexOf('<html');
    const closingTagIndex = htmlContent.indexOf('</html>');

    if (openingTagIndex !== -1 && closingTagIndex === -1) {
        alert('HTML seems to be incomplete?');
        document.getElementById('htmlCode').focus();
        return false; // Validation failed
    }

    return true; // Validation passed
}

// Event listener to trigger downloadCode on form submission
document.getElementById('appForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission
    downloadCode(); // Trigger downloadCode function
});