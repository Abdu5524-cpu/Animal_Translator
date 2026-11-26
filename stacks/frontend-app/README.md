# Mobile Expo Stack

## Local Run
1) Set API base URL (point to your API server):
```
export EXPO_PUBLIC_API_BASE_URL="http://localhost:8000"   # where backend-api is served
```
2) Install dependencies and start Expo:
```
npm install
npx expo start
```

## Notes
- The app reads API base URL from `EXPO_PUBLIC_API_BASE_URL`.
- Source code lives under `src/`.

## Web build
```
npm run build:web   # static site in dist/
```
`EXPO_PUBLIC_API_BASE_URL` is baked in at build time, so set it before building.

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
