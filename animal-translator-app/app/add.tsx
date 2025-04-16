import { useState } from 'react';
import { View, Text, TextInput, Button, Alert, StyleSheet } from 'react-native';
import { useRouter } from 'expo-router';

export default function AddAnimal() {
  const [animal, setAnimal] = useState('');
  const [translation, setTranslation] = useState('');
  const router = useRouter();

  const handleSubmit = async () => {
    if (!animal || !translation) {
      Alert.alert('Please fill in all fields');
      return;
    }
  
    try {
      console.log("Sending animal to API...");
      const res = await fetch('http://172.21.154.62/test/rest-api/api.php?endpoint=animal&action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          username: 'testuser', // change if you're using session
          animal,
          translation
        }),
      });
  
      const data = await res.json();
      console.log("API response:", data);
  
      if (data.error) throw new Error(data.error);
  
      Alert.alert('Success', 'Animal added!');
      router.push('/');
    } catch (err) {
      console.error("API call failed:", err);
      Alert.alert('Error', (err as Error).message);
    }
  };
  

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Add Animal</Text>

      <TextInput
        style={styles.input}
        placeholder="Animal"
        value={animal}
        onChangeText={setAnimal}
      />

      <TextInput
        style={styles.input}
        placeholder="Translation"
        value={translation}
        onChangeText={setTranslation}
      />

      <Button title="Submit" onPress={handleSubmit} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, justifyContent: 'center' },
  title: { fontSize: 24, marginBottom: 20, textAlign: 'center' },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    marginBottom: 12,
    padding: 10,
    borderRadius: 5,
  },
});
