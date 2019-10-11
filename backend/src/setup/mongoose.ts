import mongoose from 'mongoose';

const setupMongo = async (): Promise<void> => {
  await mongoose.connect(
    'mongodb://mongo/fifa-generator',
    {
      useCreateIndex: true,
      useNewUrlParser: true,
      useUnifiedTopology: true,
    },
  );
};

export default setupMongo;
