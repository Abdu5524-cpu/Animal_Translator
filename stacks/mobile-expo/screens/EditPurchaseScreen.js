// frontend-app/screens/EditPurchaseScreen.js

import React, { useState, useEffect } from 'react';
import { View, TextInput, Button, Text, StyleSheet, Alert } from 'react-native';
import axios from 'axios';
import { API_BASE_URL } from '../config/api';

export default function EditPurchaseScreen({ route, navigation }) {
  const { username, purchaseId, currentAnimal } = route.params;
  const purchase_id = purchaseId;
  const initialAnimal = currentAnimal;
  const [animal, setAnimal] = useState(initialAnimal);
  const [message, setMessage] = useState('');

  const handleUpdate = async () => {
    if (!animal.trim()) {
      Alert.alert('Validation', 'Animal name cannot be empty.');
      return;
    }
    try {
      const response = await axios.get(
        `${API_BASE_URL}/updatePurchase_api.php`,
        {
          params: { purchase_id, animal },
          headers: {
            Accept: 'application/json',
            'User-Agent': 'Mozilla/5.0',
          },
        }
      );
      if (response.data.success) {
        setMessage('✅ ' + response.data.message);
        setTimeout(() => navigation.navigate('Purchases', { username, refresh: true }), 1000);
      } else {
        setMessage('❌ ' + response.data.message);
      }
    } catch (err) {
      console.error(err);
      setMessage('⚠️ Network or server error');
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.label}>Edit Animal:</Text>
      <TextInput
        value={animal}
        onChangeText={setAnimal}
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
