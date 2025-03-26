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
            let lastPrayer = data.isha;

            // first, check have prayers to do today
            if(lastPrayer > now){
                // Find the next prayer
                let nextPrayer = prayerTimes.find(prayer => prayer.time > now) || prayerTimes[0];
                document.getElementById(`${nextPrayer.name}-start`).classList.add('focus');
                document.getElementById(`${nextPrayer.name}-iqadah`).classList.add('focus');

                function updateCountdown() {
                    let remainingTime = nextPrayer.time - Math.floor(Date.now() / 1000); // Recalculate current timestamp
                    let nextPrayerTime = nextPrayer.formattedTime;

                    if (remainingTime <= 0) {
                        location.reload();  // Reload the page when time reaches zero
                        return;
                    }

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
            }
            // If Isha has passed, display "No more prayers today" without reloading every second
            else {
                document.getElementById('prayer-next').innerHTML = 
                    `There are no more prayers today.`;
                document.getElementById('prayer-countdown').classList.add('hide');

                // Schedule a reload at midnight to fetch the next day's prayers
                let secondsUntilMidnight = (24 * 3600) - (now % (24 * 3600));
                setTimeout(() => location.reload(), secondsUntilMidnight * 1000);
            }
        });
});