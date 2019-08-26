import {
  Entity, PrimaryGeneratedColumn, Column, AfterLoad, BeforeUpdate, BeforeInsert, AfterUpdate,
} from 'typeorm';

@Entity()
export default class User {
  @PrimaryGeneratedColumn()
  public id!: number;

  @Column('text')
  public username!: string;

  @Column('text')
  public password!: string;

  private tempPassword!: string;

  @AfterLoad()
  @AfterUpdate()
  private loadTempPassword(): void {
    this.tempPassword = this.password;
  }

  @BeforeInsert()
  @BeforeUpdate()
  private encryptPassword(): void {
    if (this.tempPassword !== this.password) {
      this.password = Buffer.from(this.password).toString('base64');
    }
  }
}
