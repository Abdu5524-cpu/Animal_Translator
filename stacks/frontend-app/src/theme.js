// frontend-app/theme.js
import { DefaultTheme } from 'react-native-paper';

export const theme = {
  ...DefaultTheme,
  colors: {
    ...DefaultTheme.colors,
    primary: '#287d94',       // your teal
    accent: '#5e1481',        // your purple links
    background: 'rgba(255,255,255,0.9)',
    surface: '#FFFFFF',
    text: '#0f2e4d',          // dark navy
  },
  roundness: 8,               // match your card corners
  fonts: {
    ...DefaultTheme.fonts,
    regular: { fontFamily: 'System', fontWeight: '400' },
    medium:  { fontFamily: 'System', fontWeight: '500' },
    light:   { fontFamily: 'System', fontWeight: '300' },
    thin:    { fontFamily: 'System', fontWeight: '200' },
  },
};
