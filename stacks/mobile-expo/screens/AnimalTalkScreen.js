// frontend-app/screens/AnimalTalkScreen.js
import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  Pressable,
} from 'react-native';
import { ActivityIndicator, Button } from 'react-native-paper';
import { Audio } from 'expo-av';
import { theme } from '../theme';

const soundMap = {
  Dog:    require('../assets/audio/dog.m4a'),
  Cat:    require('../assets/audio/cat.m4a'),
  Horse:  require('../assets/audio/horse.m4a'),
  Bird:   require('../assets/audio/bird.m4a'),
  Pig:    require('../assets/audio/pig.m4a'),
  Monkey: require('../assets/audio/monkey.m4a'),
};

export default function AnimalTalkScreen({ navigation, route }) {
  const { animal } = route.params;
  const [mode, setMode]     = useState('idle'); // 'idle' | 'recording' | 'translating' | 'playing'
  const [soundObj, setSoundObj] = useState(null);

  const handlePressIn = () => {
    setMode('recording');
  };

  const handlePressOut = async () => {
    setMode('translating');
    await new Promise(res => setTimeout(res, 1000)); // show “Translating…” briefly

    const clip = soundMap[animal] || soundMap.Dog;
    const { sound } = await Audio.Sound.createAsync(clip);
    setSoundObj(sound);

    setMode('playing');
    await sound.playAsync();
  };

  useEffect(() => {
    return () => {
      if (soundObj) soundObj.unloadAsync();
    };
  }, [soundObj]);

  return (
    <View style={styles.container}>
      {/* 1) Idle / Recording */}
      {(mode === 'idle' || mode === 'recording') && (
        <>
          <Text style={styles.prompt}>
            {animal.charAt(0).toUpperCase() + animal.slice(1)} — hold to talk
          </Text>
          <Pressable
            onPressIn={handlePressIn}
            onPressOut={handlePressOut}
            style={[
              styles.micButton,
              mode === 'recording' && styles.micRecording,
            ]}
          >
            {mode === 'recording' ? (
              <ActivityIndicator size="large" color="#fff" />
            ) : (
              <Text style={styles.micIcon}>🎙️</Text>
            )}
          </Pressable>
        </>
      )}

      {/* 2) Translating */}
      {mode === 'translating' && (
        <>
          <Text style={styles.prompt}>Translating…</Text>
          <ActivityIndicator size="large" />
        </>
      )}

      {/* 3) Playing (generic message, no transcript) */}
      {mode === 'playing' && (
        <>
          <Text style={styles.prompt}>
            Here’s what your {animal.toLowerCase()} is saying!
          </Text>
        </>
      )}

      {/* Back button always accessible */}
      <Button onPress={() => navigation.goBack()} style={styles.back}>
        ← Back
      </Button>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex:             1,
    backgroundColor:  theme.colors.background,
    justifyContent:   'center',
    alignItems:       'center',
    padding:          24,
  },
  prompt: {
    fontSize:     20,
    fontWeight:   '600',
    color:        theme.colors.primary,
    marginBottom: 24,
    textAlign:    'center',
  },
  micButton: {
    width:           80,
    height:          80,
    borderRadius:    40,
    backgroundColor: theme.colors.primary,
    justifyContent:  'center',
    alignItems:      'center',
    marginBottom:    24,
  },
  micRecording: {
    backgroundColor: 'crimson',
  },
  micIcon: {
    fontSize: 32,
  },
  back: {
    marginTop: 16,
  },
});
