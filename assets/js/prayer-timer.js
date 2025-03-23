document.addEventListener("DOMContentLoaded", function() {
    fetch('/wp-json/prayer/v1/times')  // Fetch prayer times from WordPress API
        .then(response => response.json())
        .then(data => {
            let now = Math.floor(Date.now() / 1000); // Get current timestamp
            let prayerTimes = [
                { name: 'Fajr', time: data.fajr, formattedTime: data.fajr_time },
                { name: 'Zuhr', time: data.zuhr, formattedTime: data.zuhr_time },
                { name: 'Asr', time: data.asr, formattedTime: data.asr_time },
                { name: 'Maghrib', time: data.maghrib, formattedTime: data.maghrib_time },
                { name: 'Isha', time: data.isha, formattedTime: data.isha_time }
            ];

            //console.log(`prayerTimes = ${JSON.stringify(prayerTimes)}`)

            // Find the next prayer
            let nextPrayer = prayerTimes.find(prayer => prayer.time > now) || prayerTimes[0];

            function updateCountdown() {
                let remainingTime = nextPrayer.time - Math.floor(Date.now() / 1000);

                if (remainingTime <= 0 && nextPrayer.name !== 'Isha') {
                    location.reload();  // Reload the page when time reaches zero
                    return;
                }
                else if (remainingTime <= 0 && nextPrayer.name === 'Isha') {
                    // need to request to load tomorrow's data
                    return;
                }

                let nextPrayerTime = nextPrayer.formattedTime;

                let hours = Math.floor(remainingTime / 3600);
                let minutes = Math.floor((remainingTime % 3600) / 60);
                let seconds = remainingTime % 60;
                document.getElementById('prayer-next').innerHTML = 
                    `<strong>Next Prayer:</strong> ${nextPrayer.name} at ${nextPrayerTime}`;
                document.getElementById('prayer-countdown').innerHTML = 
                    `${hours}h ${minutes}m ${seconds}s`;
            }

            setInterval(updateCountdown, 1000); // Update countdown every second
            updateCountdown(); // Run immediately
        });
});