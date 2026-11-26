// frontend-app/screens/LoginScreen.js
import React, { useState } from 'react';
import { ScrollView, View, StyleSheet, Image, TouchableOpacity, Text } from 'react-native';
import { Card, TextInput as PaperInput, Button as PaperButton } from 'react-native-paper';
import { theme } from '../theme';
import { api, setAuthToken, errorMessage } from '../config/api';

const jakeLogo = require('../assets/images/jake.png');

export default function LoginScreen({ navigation }) {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');

  const handleLogin = async () => {
    if (!username.trim() || !password) {
      setMessage('⚠️ Enter your username and password.');
      return;
    }

    try {
      // POST keeps the password out of the URL, server logs and browser history.
      const { data } = await api.post('/login_api_get.php', {
        username: username.trim(),
        password,
      });
      setAuthToken(data.token);
      setMessage('✅ Login successful!');
      navigation.replace('Purchases', { username: data.username });
    } catch (error) {
      const failed = error?.response?.status === 401;
      setMessage(failed ? '❌ Wrong username or password.' : '⚠️ ' + errorMessage(error, 'Error connecting to server.'));
    }
  };

  return (
    <ScrollView contentContainerStyle={styles.container}>
      <Image source={jakeLogo} style={styles.logo} />

      <Card style={styles.card}>
        <Card.Content>
          <Text style={styles.title}>Login</Text>
          <Text style={styles.subtitle}>
            Welcome to the Animal Translator app!
          </Text>

          <PaperInput
            label="Username"
            value={username}
            onChangeText={setUsername}
            autoCapitalize="none"
            autoCorrect={false}
            mode="outlined"
            outlineColor={theme.colors.primary}
            activeOutlineColor={theme.colors.primary}
            style={styles.input}
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

          <PaperButton
            mode="contained"
            onPress={handleLogin}
            contentStyle={styles.buttonContent}
            style={styles.button}
          >
            Login
          </PaperButton>

          {message !== '' && (
            <Text style={styles.message}>{message}</Text>
          )}

          <TouchableOpacity
            style={styles.linkContainer}
            onPress={() => navigation.navigate('Register')}
          >
            <Text style={styles.linkText}>
              Don’t have an account?{' '}
              <Text style={styles.linkAccent}>Register here.</Text>
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
