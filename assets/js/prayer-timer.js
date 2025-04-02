document.addEventListener("DOMContentLoaded", function() {
    fetch('/wp-json/prayer/v1/times')  // Fetch prayer times from WordPress API
        .then(response => response.json())
        .then(data => {
            let now = Math.floor(Date.now() / 1000); // Get current timestamp
            let prayerTimes = [
                { name: 'Fajr', time: data?.timestamps?.['timestamp-fajr'] ?? 0, formattedTime: data?.iqamah_times?.fajr ?? 'N/A' },
                { name: 'Zuhr', time: data?.timestamps?.['timestamp-zuhr'] ?? 0, formattedTime: data?.iqamah_times?.zuhr ?? 'N/A' },
                { name: 'Asr', time: data?.timestamps?.['timestamp-asr'] ?? 0, formattedTime: data?.iqamah_times?.asr ?? 'N/A' },
                { name: 'Maghrib', time: data?.timestamps?.['timestamp-magrib'] ?? 0, formattedTime: data?.iqamah_times?.maghrib ?? 'N/A' },
                { name: 'Isha', time: data?.timestamps?.['timestamp-isha'] ?? 0, formattedTime: data?.iqamah_times?.isha ?? 'N/A' }
            ];            
            let lastPrayer = data?.timestamps?.['timestamp-isha'] ?? 0 // data['timestamps']['timestamp-isha'];

            // first, check have prayers to do today
            if(lastPrayer > now){
                // Find the next prayer
                let nextPrayer = prayerTimes.find(prayer => prayer.time > now) || prayerTimes[0];
                console.log(`${nextPrayer.name}-cell`)
                document.getElementById(`${nextPrayer.name}-cell`).classList.add('focus');

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
                        `<strong>Next Prayer:</strong> ${nextPrayer.name} Iqamah at ${nextPrayerTime}`;
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