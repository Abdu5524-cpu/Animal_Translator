import { BASE_URL } from "../constants/api";

export async function getAllAnimals() {
  try {
    const res = await fetch(`${BASE_URL}/rest-api/api.php?endpoint=animal&action=read`, {
      headers: {
        Authorization: "Bearer jeff",  // replace with real token
      }
    });

    console.log("GET status:", res.status);

    const text = await res.text();
    console.log("RAW API RESPONSE:", text);

    const data = JSON.parse(text);

    if (!res.ok || data.error) {
      throw new Error(data.error || "Failed to load animals");
    }

    return data;
  } catch (error) {
    console.error("Error fetching animals:", error);
    return [];
  }
}
