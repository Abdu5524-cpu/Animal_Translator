// frontend-app/App.js
import React from 'react';
import { Image, StyleSheet } from 'react-native';
import { GestureHandlerRootView } from 'react-native-gesture-handler';
import { Provider as PaperProvider, Appbar } from 'react-native-paper';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import { theme } from './theme';                    // ← corrected path
import LoginScreen        from './screens/LoginScreen';
import RegisterScreen     from './screens/RegisterScreen';
import PurchaseListScreen from './screens/PurchaseListScreen';
import MakePurchaseScreen from './screens/MakePurchaseScreen';
import EditPurchaseScreen from './screens/EditPurchaseScreen';
import AnimalTalkScreen   from './screens/AnimalTalkScreen';

const Stack = createNativeStackNavigator();
const jakeLogo = require('./assets/images/jake.png');

function Header({ navigation, back }) {
  return (
    <Appbar.Header style={styles.header}>
      {back ? <Appbar.BackAction onPress={navigation.goBack} /> : null}
      <Appbar.Content title="Animal Translator" titleStyle={styles.title} />
      <Image source={jakeLogo} style={styles.logo} resizeMode="contain" />
    </Appbar.Header>
  );
}

export default function App() {
  return (
    <GestureHandlerRootView style={{ flex: 1 }}>
      <PaperProvider theme={theme}>
        <NavigationContainer>
          <Stack.Navigator
            initialRouteName="Login"
            screenOptions={{
              header: props => <Header {...props} />,
              contentStyle: { backgroundColor: 'transparent' },
            }}
          >
            <Stack.Screen name="Login"        component={LoginScreen}      options={{ title: 'Login' }} />
            <Stack.Screen name="Register"     component={RegisterScreen}   options={{ title: 'Register' }} />
            <Stack.Screen name="Purchases"    component={PurchaseListScreen} options={{ title: 'Your Purchases' }} />
            <Stack.Screen name="MakePurchase" component={MakePurchaseScreen} options={{ title: 'New Purchase' }} />
            <Stack.Screen name="EditPurchase" component={EditPurchaseScreen} options={{ title: 'Edit Purchase' }} />
            <Stack.Screen name="AnimalTalk"   component={AnimalTalkScreen}  options={{ title: 'Animal Talk' }} />
          </Stack.Navigator>
        </NavigationContainer>
      </PaperProvider>
    </GestureHandlerRootView>
  );
}

const styles = StyleSheet.create({
  header: {
    backgroundColor: '#FFFFFF',
    elevation: 2,
  },
  title: {
    color: theme.colors.primary,
    fontSize: 20,
    fontWeight: '500',
  },
  logo: {
    width: 36,
    height: 36,
    marginRight: 12,
  },
});
