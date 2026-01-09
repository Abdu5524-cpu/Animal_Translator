// frontend-app/screens/RegisterScreen.js
import React, { useState } from 'react';
import {
  ScrollView,
  View,
  StyleSheet,
  Image,
  TouchableOpacity,
  Text,
} from 'react-native';
import { Card, TextInput as PaperInput, Button as PaperButton } from 'react-native-paper';
import axios from 'axios';
import { theme } from '../theme';
import { API_BASE_URL } from '../config/api';

const jakeLogo = require('../assets/images/jake.png');

export default function RegisterScreen({ navigation }) {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [confirm, setConfirm] = useState('');
  const [message, setMessage] = useState('');

  const handleRegister = async () => {
    if (!username.trim() || !password || password !== confirm) {
      setMessage('⚠️ Please fill out all fields and make sure passwords match.');
      return;
    }

    try {
      const res = await axios.get(
        `${API_BASE_URL}/register_api_get.php`,
        {
          params: { username, password, confirm_password: confirm },
          headers: {
            Accept: 'application/json',
            'User-Agent': 'Mozilla/5.0',
          },
        }
      );
      if (res.data.success) {
        setMessage('✅ Registration successful! Redirecting...');
        setTimeout(() => navigation.replace('Login'), 1500);
      } else {
        setMessage(`❌ ${res.data.message}`);
      }
    } catch (err) {
      console.error(err);
      setMessage('⚠️ Network error, please try again.');
    }
  };

  return (
    <ScrollView contentContainerStyle={styles.container}>
      <Image source={jakeLogo} style={styles.logo} />

      <Card style={styles.card}>
        <Card.Content>
          <Text style={styles.title}>Register</Text>
          <Text style={styles.subtitle}>
            Create your Animal Translator account
          </Text>

          <PaperInput
            label="Username"
            value={username}
            onChangeText={setUsername}
            mode="outlined"
            outlineColor={theme.colors.primary}
            activeOutlineColor={theme.colors.primary}
            style={styles.input}
            autoCapitalize="none"
          />

          <PaperInput
            label="Password"
            value={password}
            onChangeText={setPassword}
            secureTextEntry
            mode="outlined"
            outlineColor={theme.colors.primary}
            activeOutlineColor={theme.colors.primary}
            style={styles.input}
          />

          <PaperInput
            label="Confirm Password"
            value={confirm}
            onChangeText={setConfirm}
            secureTextEntry
            mode="outlined"
            outlineColor={theme.colors.primary}
            activeOutlineColor={theme.colors.primary}
            style={styles.input}
          />

          <PaperButton
            mode="contained"
            onPress={handleRegister}
            contentStyle={styles.buttonContent}
            style={styles.button}
          >
            REGISTER
          </PaperButton>

          {message ? (
            <Text style={styles.message}>{message}</Text>
          ) : null}

          <TouchableOpacity
            style={styles.linkContainer}
            onPress={() => navigation.replace('Login')}
          >
            <Text style={styles.linkText}>
              Already have an account?{' '}
              <Text style={styles.linkAccent}>Login here.</Text>
            </Text>
          </TouchableOpacity>
        </Card.Content>
      </Card>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flexGrow: 1,
    backgroundColor: '#f3f4f5',
    alignItems: 'center',
    justifyContent: 'center',
    padding: 16,
  },
  logo: {
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: '#fff',
    padding: 8,
    marginBottom: 16,
  },
  card: {
    width: '100%',
    maxWidth: 400,
    borderRadius: 12,
    elevation: 4,
  },
  title: {
    fontSize: 24,
    fontWeight: '700',
    textAlign: 'center',
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 16,
    fontWeight: '400',
    textAlign: 'center',
    color: theme.colors.primary,
    marginBottom: 24,
  },
  input: {
    marginBottom: 16,
  },
  button: {
    marginTop: 8,
    borderRadius: 4,
  },
  buttonContent: {
    paddingVertical: 8,
  },
  message: {
    marginTop: 12,
    textAlign: 'center',
    color: theme.colors.text,
  },
  linkContainer: {
    marginTop: 12,
    alignItems: 'center',
  },
  linkText: {
    fontSize: 14,
    color: theme.colors.text,
  },
  linkAccent: {
    color: theme.colors.accent,
    textDecorationLine: 'underline',
  },
});
