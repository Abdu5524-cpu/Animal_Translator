import { useState } from 'react';
import { View, Text, TextInput, Button, StyleSheet, Alert } from 'react-native';
import { useRouter, Link } from 'expo-router';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function LoginScreen() {
  const router = useRouter();

  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = async () => {
    if (!username || !password) {
      Alert.alert("Error", "All fields are required.");
      return;
    }

    try {
      const response = await fetch("https://animals.great-site.net/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json", // tell backend to return JSON
        },
        body: JSON.stringify({ username, password }),
      });

      const text = await response.text();
      console.log("Login response text:", text);

      const data = JSON.parse(text);

      if (data.success && data.token) {
        // ✅ Store token
        await AsyncStorage.setItem('token', data.token);

        Alert.alert("Success", "Logged in!");
        router.push("/");
      } else {
        Alert.alert("Login Failed", data.error || "Invalid credentials.");
      }
    } catch (error) {
      Alert.alert("Error", "Something went wrong.");
      console.error("Login error:", error);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.heading}>Login</Text>
      <TextInput
        placeholder="Username"
        value={username}
        onChangeText={setUsername}
        style={styles.input}
      />
      <TextInput
        placeholder="Password"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
        style={styles.input}
      />
      <Button title="Login" onPress={handleLogin} />
      <Link href="/register" style={styles.link}>
        Don't have an account? Register here
      </Link>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: "center", padding: 20 },
  heading: { fontSize: 22, marginBottom: 20 },
  input: { borderWidth: 1, marginBottom: 10, padding: 8 },
  link: { marginTop: 10, color: "blue" },
});
