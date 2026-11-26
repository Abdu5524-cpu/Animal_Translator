// frontend-app/screens/MakePurchaseScreen.js
import React, { useState, useRef } from 'react';
import {
  View,
  StyleSheet,
  Text,
  TouchableOpacity,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { Card, Button as PaperButton, Snackbar } from 'react-native-paper';
import { Picker } from '@react-native-picker/picker';
import ConfettiCannon from 'react-native-confetti-cannon';
import { theme } from '../theme';
import { api, errorMessage, isUnauthorized } from '../config/api';

const animalMessages = {
  Dog: "🐶 Woof! Let's talk about squirrels.",
  Cat: "🐱 Meow. I demand treats immediately.",
  Horse: "🐴 Neigh... I could use a carrot.",
  Bird: "🐦 Tweet tweet! Sing me a song!",
  Pig: "🐷 Oink! Where's the slop?",
  Monkey: "🐵 Ooh ooh aah aah!",
  Other: "✨ Thanks for picking something exotic!",
};

export default function MakePurchaseScreen({ navigation }) {
  const [selectedAnimal, setSelectedAnimal] = useState('');
  const [previewMessage, setPreviewMessage] = useState('');
  const [snackbarVisible, setSnackbarVisible] = useState(false);
  const [confettiCount, setConfettiCount] = useState(0);

  const handlePurchase = async () => {
    if (!selectedAnimal) {
      setPreviewMessage('Please choose an animal.');
      return;
    }

    try {
      // The server takes the username from the login token, not from the request.
      await api.post('/makePurchase_api.php', { animal: selectedAnimal });
      setSnackbarVisible(true);
      setConfettiCount(c => c + 1);
      // Go back to the existing list screen (it refreshes when it regains focus)
      // instead of stacking a second copy of it.
      setTimeout(() => navigation.goBack(), 2000);
    } catch (err) {
      if (isUnauthorized(err)) {
        navigation.replace('Login');
        return;
      }
      setPreviewMessage(`❌ ${errorMessage(err, 'Network error, please try again.')}`);
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.select({ ios: 'padding', android: undefined })}
      style={styles.container}
    >
      {confettiCount > 0 && (
        <ConfettiCannon
          count={100}
          origin={{ x: -10, y: 0 }}
          autoStart
          fadeOut
          onAnimationEnd={() => setConfettiCount(0)}
        />
      )}

      <Card style={styles.card}>
        <Card.Content>
          <Text style={styles.title}>Buy a New Animal Translating Software</Text>
          <Text style={styles.prompt}>
            What animal do you want to buy software for?
          </Text>

          <View style={styles.pickerWrapper}>
            <Picker
              selectedValue={selectedAnimal}
              onValueChange={value => {
                setSelectedAnimal(value);
                setPreviewMessage(animalMessages[value] || '');
              }}
              style={styles.picker}
              itemStyle={styles.pickerItem}
            >
              <Picker.Item label="-- Select an Animal --" value="" />
              <Picker.Item label="🐶 Dog" value="Dog" />
              <Picker.Item label="🐱 Cat" value="Cat" />
              <Picker.Item label="🐴 Horse" value="Horse" />
              <Picker.Item label="🐦 Bird" value="Bird" />
              <Picker.Item label="🐷 Pig" value="Pig" />
              <Picker.Item label="🐵 Monkey" value="Monkey" />
              <Picker.Item label="Other" value="Other" />
            </Picker>
          </View>

          {previewMessage ? (
            <View style={styles.messageBubble}>
              <Text style={styles.messageText}>{previewMessage}</Text>
            </View>
          ) : null}

          <PaperButton
            mode="contained"
            onPress={handlePurchase}
            contentStyle={styles.buttonContent}
            style={styles.button}
          >
            Submit
          </PaperButton>

          <TouchableOpacity onPress={() => navigation.goBack()}>
            <Text style={styles.link}>Return Home</Text>
          </TouchableOpacity>
        </Card.Content>
      </Card>

      <Snackbar
        visible={snackbarVisible}
        duration={1800}
        onDismiss={() => setSnackbarVisible(false)}
      >
        🎉 Congrats! Your purchase was successful! 🎉
      </Snackbar>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f3f4f5',
    padding: 16,
    justifyContent: 'center',
  },
  card: {
    borderRadius: 12,
    elevation: 4,
  },
  title: {
    fontSize: 22,
    fontWeight: '700',
    color: theme.colors.text,
    textAlign: 'center',
    marginBottom: 16,
  },
  prompt: {
    fontSize: 16,
    color: theme.colors.primary,
    textAlign: 'center',
    marginBottom: 12,
  },
  pickerWrapper: {
    borderWidth: 1,
    borderColor: theme.colors.primary,
    borderRadius: 6,
    overflow: 'hidden',
    marginBottom: 16,
  },
  picker: {
    height: 50,
    backgroundColor: '#fff',
  },
  pickerItem: {
    height: 50,
  },
  messageBubble: {
    backgroundColor: '#E0F7FA',
    padding: 12,
    borderRadius: 8,
    marginBottom: 16,
  },
  messageText: {
    color: theme.colors.text,
    fontSize: 14,
  },
  button: {
    borderRadius: 4,
    marginBottom: 12,
  },
  buttonContent: {
    paddingVertical: 8,
  },
  link: {
    color: theme.colors.accent,
    textAlign: 'center',
    textDecorationLine: 'underline',
    marginTop: 8,
  },
});
