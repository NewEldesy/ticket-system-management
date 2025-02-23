SELECT users.username, AVG(ratings.rating) as avg_rating
FROM users
JOIN ratings ON users.id = ratings.technician_id
GROUP BY users.id;
