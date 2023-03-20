function resetGame() {
    const inputs = document.querySelectorAll('input[type="text"]');
    for (const input of inputs) {
        input.value = '';
    }
    const resultMessage = document.getElementById('resultMessage');
    resultMessage.style.display = 'none';

    const playAgainButton = document.getElementById('playAgainButton');
    playAgainButton.style.display = 'none';
}


function validateForm() {
    const playAgainButton = document.getElementById('playAgainButton');
    playAgainButton.style.display = 'none';
    
    const inputs = document.querySelectorAll('input[type="text"]');
    let xCount = 0;
    let oCount = 0;
    let emptyCount = 0;
    for (const input of inputs) {
        if (input.value.trim().toLowerCase() === 'x') {
            xCount++;
        } else if (input.value.trim().toLowerCase() === 'o') {
            oCount++;
        } else {
            emptyCount++;
        }
    }

    if (emptyCount === 9 && xCount === 0) {
        alert('Please place \'x\'.');
        return false;
    }

    if (xCount === oCount) {
        alert('Please place an \'x\' before clicking Play.');
        return false;
    }

    if (xCount !== oCount + 1) {
        alert('It is not your turn. Please wait for the computer to play.');
        return false;
    }

    return true;
}

