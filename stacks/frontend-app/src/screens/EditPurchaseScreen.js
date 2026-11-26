// frontend-app/screens/EditPurchaseScreen.js

import React, { useState } from 'react';
import { View, TextInput, Button, Text, StyleSheet } from 'react-native';
import { api, errorMessage, isUnauthorized } from '../config/api';

export default function EditPurchaseScreen({ route, navigation }) {
  const { purchaseId, currentAnimal } = route.params;
  const purchase_id = purchaseId;
  const initialAnimal = currentAnimal;
  const [animal, setAnimal] = useState(initialAnimal);
  const [message, setMessage] = useState('');

  const handleUpdate = async () => {
    if (!animal.trim()) {
      setMessage('❌ Animal name cannot be empty.');
      return;
    }
    try {
      const { data } = await api.post('/updatePurchase_api.php', {
        purchase_id,
        animal: animal.trim(),
      });
      setMessage('✅ ' + data.message);
      setTimeout(() => navigation.goBack(), 1000);
    } catch (err) {
      if (isUnauthorized(err)) {
        navigation.replace('Login');
        return;
      }
      setMessage('❌ ' + errorMessage(err));
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.label}>Edit Animal:</Text>
      <TextInput
        value={animal}
        onChangeText={setAnimal}
        maxLength={20}
        style={styles.input}
      />
      <Button title="Save Changes" onPress={handleUpdate} />
      {message ? <Text style={styles.message}>{message}</Text> : null}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { padding: 20, flex: 1, justifyContent: 'center', backgroundColor: '#fff' },
  label: { fontSize: 18, marginBottom: 10 },
  input: {
    borderWidth: 1, borderColor: '#aaa', padding: 10,
    marginBottom: 20, borderRadius: 5,
  },
  message: { marginTop: 20, textAlign: 'center' },
});
