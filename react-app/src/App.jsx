import { useState, useEffect } from 'react';
import './App.css';

function App() {
    const [tracks, setTracks] = useState([]);
    const [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const analyticsUrl = 'http://localhost:9999/api/analytics';

    const fetchTracks = async () => {
        try {
            setLoading(true);
            setError(null);

            const queryParams = new URLSearchParams();
            if (startDate) queryParams.append('start_date', `${startDate}T00:00:00Z`);
            if (endDate) queryParams.append('end_date', `${endDate}T23:59:59Z`);

            const response = await fetch(`${analyticsUrl}?${queryParams.toString()}`);
            if (!response.ok) {
                throw new Error(`Error: ${response.statusText}`);
            }

            const data = await response.json();
            setTracks(data);
        } catch (err) {
            setError('Please enter a valid date range.');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchTracks();
    }, []);

    const handleSearch = (e) => {
        e.preventDefault();
        fetchTracks();
    };

    return (
        <div className="App">
            <h1>Unique Tracks Analytics</h1>

            <form onSubmit={handleSearch}>
                <div className="date-filter">
                    <label>
                        Start Date:
                        <input
                            type="date"
                            value={startDate}
                            onChange={(e) => setStartDate(e.target.value)}
                        />
                    </label>
                    <label>
                        End Date:
                        <input
                            type="date"
                            value={endDate}
                            onChange={(e) => setEndDate(e.target.value)}
                        />
                    </label>
                    <button type="submit">Search</button>
                </div>
            </form>

            {loading && <p>Loading...</p>}
            {error && <p className="error">{error}</p>}

            {!loading && !error && tracks.length > 0 && (
                <div className="tracks-list">
                    <h2>Tracks</h2>
                    <ul>
                        {tracks.map((track, index) => (
                            <li key={index}>
                                <strong>URL:</strong> {track.url} |
                                <strong> IP:</strong> {track.ip} |
                                <strong> Referrer:</strong> {track.referrer ?? 'N/A'} |
                                <strong> User Agent:</strong> {track.userAgent ?? 'N/A'} |
                                <strong> Geo Location:</strong> {track.geoLocation
                                ? `${track.geoLocation.country}, ${track.geoLocation.region}, ${track.geoLocation.city}`
                                : 'N/A'} |
                                <strong> Browser:</strong> {track.browser
                                ? `${track.browser.browser} ${track.browser.version} (${track.browser.os})`
                                : 'N/A'} |
                                <strong> Timestamp:</strong> {new Date(track.timestamp).toLocaleString()} |
                                <strong> Total Clicks:</strong> {track.total_clicks}
                            </li>
                        ))}
                    </ul>
                </div>
            )}


            {!loading && !error && tracks.length === 0 && (
                <p>No tracks found for the given period.</p>
            )}
        </div>
    );
}

export default App;
