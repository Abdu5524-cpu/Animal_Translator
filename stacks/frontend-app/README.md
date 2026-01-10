# Mobile Expo Stack

## Local Run
1) Set API base URL (point to your API server):
```
export EXPO_PUBLIC_API_BASE_URL="http://localhost:8000"
```
2) Install dependencies and start Expo:
```
npm install
npx expo start
```

## Notes
- The app reads API base URL from `EXPO_PUBLIC_API_BASE_URL`.
- Source code lives under `src/`.

## Deployment
1) Install and login to EAS:
```
npm install -g eas-cli
eas login
```
2) Configure builds and build:
```
eas build:configure
eas build -p android
```
Use `-p ios` for iOS builds.
