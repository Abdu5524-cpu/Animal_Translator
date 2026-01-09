import { useEffect, useState } from 'react';
import { View, Text, TextInput, Button, StyleSheet, Alert } from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { getAnimalById, updateAnimal } from '../api/animalApi';

export default function EditScreen() {
  const router = useRouter();
  const { id } = useLocalSearchParams();

  const [animal, setAnimal] = useState('');
  const [translation, setTranslation] = useState('');

  useEffect(() => {
    if (id) {
      getAnimalById(id.toString())
        .then(data => {
          setAnimal(data.animal);
          setTranslation(data.translation);
        })
        .catch(error => {
          console.error(error);
          Alert.alert("Error", "Failed to load data.");
        });
    }
  }, [id]);

  const handleUpdate = async () => {
    try {
      const data = await updateAnimal(id!.toString(), animal, translation);

      if (data.success) {
        Alert.alert("Updated!", "Translation updated.");
        router.push("/");
      } else {
        Alert.alert("Error", data.message || "Something went wrong.");
      }
    } catch (error) {
      console.error(error);
      Alert.alert("Error", "Failed to update entry.");
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.heading}>Edit Animal</Text>
      <TextInput
        value={animal}
        onChangeText={setAnimal}
        placeholder="Animal"
        style={styles.input}
      />
      <TextInput
        value={translation}
        onChangeText={setTranslation}
        placeholder="Translation"
        style={styles.input}
      />
      <Button title="Update" onPress={handleUpdate} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: 'center', padding: 20 },
  heading: { fontSize: 20, marginBottom: 20 },
  input: { borderWidth: 1, marginBottom: 10, padding: 10, borderRadius: 5 },
});
