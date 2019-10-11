const roles = {
  admin: {
    id: 'admin',
    resource: [
      {
        id: 'tournaments',
        permissions: ['create', 'read', 'update', 'delete'],
      },
    ],
  },
  guest: {
    id: 'guest',
    resource: [
      {
        id: 'tournaments',
        permissions: ['read'],
      },
    ],
  },
};

export default roles;
