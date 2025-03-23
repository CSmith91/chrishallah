document.addEventListener("DOMContentLoaded", function() {
    fetch('/wp-json/prayer/v1/times')  // Fetch prayer times from WordPress API
        .then(response => response.json())
        .then(data => {
            let now = Math.floor(Date.now() / 1000); // Get current timestamp
            let prayerTimes = [
                { name: 'Fajr', time: data.fajr },
                { name: 'Zuhr', time: data.zuhr },
                { name: 'Asr', time: data.asr },
                { name: 'Maghrib', time: data.maghrib },
                { name: 'Isha', time: data.isha }
            ];

            console.log(`prayerTimes = ${JSON.stringify(prayerTimes)}`)

            // Find the next prayer
            let nextPrayer = prayerTimes.find(prayer => prayer.time > now) || prayerTimes[0];

            function updateCountdown() {
                let remainingTime = nextPrayer.time - Math.floor(Date.now() / 1000);
                // if (remainingTime <= 0) {
                //     location.reload();  // Reload the page when time reaches zero
                //     return;
                // }

                let hours = Math.floor(remainingTime / 3600);
                let minutes = Math.floor((remainingTime % 3600) / 60);
                let seconds = remainingTime % 60;
                document.getElementById('prayer-countdown').innerHTML = 
                    `Next: ${nextPrayer.name} in ${hours}h ${minutes}m ${seconds}s`;
            }

            setInterval(updateCountdown, 60000); // Update countdown every minute
            updateCountdown(); // Run immediately
        });
});
