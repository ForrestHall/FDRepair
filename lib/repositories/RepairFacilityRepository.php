<?php
/**
 * Diesel repair listings (fdr_listings) by distance.
 */
class RepairFacilityRepository {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    /**
     * Find listings within $dist miles of (lat, lng).
     */
    public function findNear(float $lat, float $lng, int $dist = 50): array {
        $result = [];
        $stmt = $this->conn->prepare("
            SELECT *, ST_Distance_Sphere(POINT(?, ?), POINT(longitude, latitude)) * 0.000621371192 AS distance_in_miles
            FROM fdr_listings
            WHERE latitude IS NOT NULL AND longitude IS NOT NULL
            HAVING distance_in_miles <= ?
            ORDER BY COALESCE(VERIFIED, 0) DESC, distance_in_miles ASC
        ");
        if (!$stmt) return $result;
        $stmt->bind_param('ddi', $lng, $lat, $dist);
        $stmt->execute();
        $fetch = $stmt->get_result();
        if (!$fetch) return $result;

        while ($row = $fetch->fetch_array()) {
            $d = isset($row['distance_in_miles']) ? round((float) $row['distance_in_miles'], 2) : null;
            $result[] = [
                'NAME' => $row['NAME'] ?? '',
                'ADDRESS' => $row['ADDRESS'] ?? '',
                'SITE' => $row['SITE'] ?? '',
                'ZIPCODE' => $row['ZIPCODE'] ?? '',
                'RATE' => $row['RATE'] ?? '',
                'PHONE' => $row['PHONE'] ?? '',
                'MAP' => $row['MAP'] ?? '',
                'MOBILE' => $row['MOBILE'] ?? '',
                'VERIFIED' => $row['VERIFIED'] ?? '',
                'distance_in_miles' => $d,
                'LAT' => isset($row['latitude']) ? (float) $row['latitude'] : null,
                'LNG' => isset($row['longitude']) ? (float) $row['longitude'] : null,
                'PLACE' => $row['PLACE'] ?? null,
            ];
        }
        return $result;
    }
}
