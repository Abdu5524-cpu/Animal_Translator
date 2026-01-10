// frontend-app/screens/PurchaseListScreen.js
import React, { useState, useEffect, useCallback } from 'react';
import {
  View,
  StyleSheet,
  Text,
  FlatList,
  Alert,
} from 'react-native';
import {
  ActivityIndicator,
  Searchbar,
  FAB,
  Card,
  Button as PaperButton,
  IconButton,
} from 'react-native-paper';
import axios from 'axios';
import { Swipeable } from 'react-native-gesture-handler';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { theme } from '../theme';
import { API_BASE_URL } from '../config/api';

export default function PurchaseListScreen({ navigation, route }) {
  const { username } = route.params;
  const [purchases, setPurchases] = useState([]);
  const [filtered, setFiltered] = useState([]);
  const [loading, setLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [sortOrder, setSortOrder] = useState('newest');
  const [error, setError] = useState(null);

  const fetchPurchases = useCallback(async () => {
    setLoading(true);
    try {
      const { data } = await axios.get(
        `${API_BASE_URL}/getPurchase_api.php?username=${encodeURIComponent(
          username
        )}`
      );
      if (data.success) {
        setPurchases(data.purchases);
        setError(null);
      } else {
        setPurchases([]);
        setError(data.message);
      }
    } catch {
      setError('⚠️ Error fetching purchases.');
    } finally {
      setLoading(false);
    }
  }, [username]);

  useEffect(() => {
    fetchPurchases();
  }, [fetchPurchases]);

  useEffect(() => {
    let list = purchases.filter(item =>
      item.animal.toLowerCase().includes(searchQuery.toLowerCase())
    );
    list.sort((a, b) => {
      const da = new Date(a.time_date);
      const db = new Date(b.time_date);
      return sortOrder === 'newest' ? db - da : da - db;
    });
    setFiltered(list);
  }, [purchases, searchQuery, sortOrder]);

  const handleRefund = async id => {
    try {
      const res = await axios.get(
        `${API_BASE_URL}/refundPurchase_api.php?purchase_id=${id}`
      );
      if (res.data.success) {
        Alert.alert('Refunded', res.data.message);
        fetchPurchases();
      } else {
        Alert.alert('Error', res.data.message);
      }
    } catch {
      Alert.alert('Error', 'Network or server error');
    }
  };

  const handleEdit = (id, animal) => {
    navigation.navigate('EditPurchase', { username, purchaseId: id, currentAnimal: animal });
  };

  const renderItem = ({ item, index }) => {
    const showBadge = index === 0;

    const leftActions = () => (
      <PaperButton
        mode="contained"
        onPress={() => handleEdit(item.purchase_id, item.animal)}
        style={styles.editAction}
      >
        <Icon name="pencil" size={20} color="#fff" /> Edit
      </PaperButton>
    );
    const rightActions = () => (
      <PaperButton
        mode="contained"
        onPress={() => handleRefund(item.purchase_id)}
        style={styles.refundAction}
      >
        <Icon name="undo" size={20} color="#fff" /> Refund
      </PaperButton>
    );

    return (
      <Swipeable
        renderLeftActions={leftActions}
        renderRightActions={rightActions}
      >
        <Card style={styles.card}>
          <Card.Content>
            <View style={styles.cardHeader}>
              <Text style={styles.animal}>
                <Icon name="bone" size={16} />{' '}
                {item.animal.charAt(0).toUpperCase() + item.animal.slice(1)}
              </Text>
              {showBadge && (
                <Text style={styles.badge}>
                  {sortOrder === 'newest' ? 'Newest' : 'Oldest'}
                </Text>
              )}
            </View>
            <Text style={styles.date}>Purchased: {item.time_date}</Text>

            {/* Talk Now button */}
            <PaperButton
              mode="contained"
              onPress={() =>
                navigation.navigate('AnimalTalk', { animal: item.animal })
              }
              style={styles.talkAction}
              contentStyle={styles.talkButtonContent}
            >
              Talk Now
            </PaperButton>
          </Card.Content>
        </Card>
      </Swipeable>
    );
  };

  if (loading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Header & Toolbar */}
      <View style={styles.headerContainer}>
        <View style={styles.headerRow}>
          <Text style={styles.greeting}>Welcome, {username}!</Text>
          <IconButton
            icon="logout"
            color={theme.colors.primary}
            size={24}
            onPress={() => navigation.replace('Login')}
          />
        </View>
        <Text style={styles.subheading}>Your Animal Software Purchases:</Text>

        <Searchbar
          placeholder="Search by animal..."
          onChangeText={setSearchQuery}
          value={searchQuery}
          style={styles.searchbar}
          icon="magnify"
        />

        <PaperButton
          mode="outlined"
          onPress={() =>
            setSortOrder(prev => (prev === 'newest' ? 'oldest' : 'newest'))
          }
          style={styles.sortButton}
        >
          {sortOrder === 'newest' ? 'Newest ↕︎' : 'Oldest ↕︎'}
        </PaperButton>
      </View>

      {error ? (
        <Text style={styles.error}>{error}</Text>
      ) : filtered.length === 0 ? (
        <View style={styles.emptyContainer}>
          <Text style={styles.emptyText}>
            You haven’t bought any animals yet! Tap + to get started.
          </Text>
        </View>
      ) : (
        <FlatList
          data={filtered}
          keyExtractor={item => item.purchase_id.toString()}
          renderItem={renderItem}
          contentContainerStyle={styles.listContent}
        />
      )}

      {/* Floating Add Button */}
      <FAB
        style={styles.fab}
        icon="plus"
        onPress={() => navigation.navigate('MakePurchase', { username })}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'transparent',
    padding: 16,
  },
  headerContainer: {
    paddingBottom: 16,
  },
  headerRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 4,
  },
  greeting: {
    fontSize: 20,
    fontWeight: '600',
    color: theme.colors.primary,
  },
  subheading: {
    fontSize: 14,
    color: theme.colors.text,
    marginBottom: 8,
  },
  searchbar: {
    marginBottom: 8,
    borderRadius: 4,
  },
  sortButton: {
    alignSelf: 'flex-start',
    marginBottom: 16,
    borderColor: theme.colors.primary,
  },
  listContent: {
    paddingBottom: 80,
  },
  card: {
    backgroundColor: 'rgba(172,235,235,0.3)',
    borderLeftWidth: 4,
    borderLeftColor: theme.colors.primary,
    marginVertical: 8,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 4,
  },
  animal: {
    fontSize: 16,
    fontWeight: '500',
    color: theme.colors.primary,
  },
  badge: {
    fontSize: 12,
    color: theme.colors.primary,
    borderWidth: 1,
    borderColor: theme.colors.primary,
    borderRadius: 4,
    paddingHorizontal: 6,
    paddingVertical: 2,
  },
  date: {
    fontSize: 12,
    color: theme.colors.text,
  },
  talkAction: {
    marginTop: 8,
    borderRadius: 4,
    backgroundColor: theme.colors.accent,
    justifyContent: 'center',
  },
  talkButtonContent: {
    paddingVertical: 6,
  },
  refundAction: {
    justifyContent: 'center',
    backgroundColor: '#c94c4c',
  },
  editAction: {
    justifyContent: 'center',
    backgroundColor: theme.colors.primary,
  },
  fab: {
    position: 'absolute',
    right: 16,
    bottom: 16,
    backgroundColor: theme.colors.primary,
  },
  center: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyText: {
    fontSize: 16,
    color: theme.colors.text,
    textAlign: 'center',
  },
  error: {
    color: 'red',
    textAlign: 'center',
  },
});
