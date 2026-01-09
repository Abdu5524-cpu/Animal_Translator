import { useEffect, useState } from 'react';
import { View, Text, FlatList, Button, Alert, StyleSheet } from 'react-native';
import { useRouter } from 'expo-router';
import { getAllAnimals } from '../api/animalApi';

interface Animal {
  purchase_id: number;
  animal: string;
  translation: string;
  time_date: string; // optional, for later
}


export default function HomeScreen() {
  const router = useRouter();
  const [animals, setAnimals] = useState<Animal[]>([]);

  const fetchAnimals = async () => {
    try {
      const data = await getAllAnimals();
      setAnimals(data);
    } catch (error) {
      Alert.alert("Error", "Failed to load data.");
    }
  };

  useEffect(() => {
    fetchAnimals();
  }, []);

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Animal Translations</Text>
      <Button title="Add Animal" onPress={() => router.push('/add')} />
      <Button title="Logout" onPress={() => router.push('/login')} />

      {animals.length === 0 && (
        <Text style={{ marginTop: 20 }}>No animal translations found.</Text>
      )}

      <FlatList
        data={animals}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <View style={styles.card}>
            <Text>{item.animal}: {item.translation}</Text>
            <Button title="Edit" onPress={() => router.push(`/edit?id=${item.id}`)} />
          </View>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20 },
  title: { fontSize: 24, fontWeight: "bold", marginBottom: 20 },
  card: {
    padding: 10,
    marginBottom: 10,
    backgroundColor: "#f1f1f1",
    borderRadius: 8,
  },
});
