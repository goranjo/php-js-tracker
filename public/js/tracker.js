(function () {
    const TRACKER_ENDPOINT = "http://localhost:9999/api/track";

    const getTimestamp = () => new Date().toISOString();

    const trackPayload = () => {
        return {
            url: window.location.href,
            referrer: document.referrer,
            user_agent: navigator.userAgent,
            timestamp: getTimestamp(),
        };
    };

    const sendTrackingData = async (payload) => {
        try {
            const response = await fetch(TRACKER_ENDPOINT, {
                method: "POST",
                mode: "cors",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                console.error("Tracking failed:", response.statusText);
            }
        } catch (error) {
            console.error("Error sending tracking data:", error);
        }
    };

    const trackVisit = () => {
        const payload = trackPayload();
        console.log({payload});
        sendTrackingData(payload).then((r) => {
            console.log("Tracking successful", r);
        }).catch(() => {
            console.log("Tracking failed");
        });
    };

    trackVisit();
})();
